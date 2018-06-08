if ($('#shoutbox-main').length) {
    last_time = -1;
    editing_shout = -1;
    private_user = -1;
    current_context = 'shoutbox';
    csrf = $('meta[name="csrf-token"]').attr('content');
    smileys = {};
    turndownService = new TurndownService();
    window.newShouts = function() {
        $.ajax({
            type: "GET",
            url: '/chat/timer',
            success: function(timestamp) {
                timestamp = parseInt(timestamp);
                if (timestamp > last_time) updateShouts(timestamp);         
            }
        });
    }

    window.updateShouts = function(timestamp) {
        last_time = timestamp;
        if (private_user == -1) updateAllShouts()
        else                    updatePrivateShouts()
    }

    window.updateAllShouts = function() {
        $.ajax({
            type: "GET",
            url: '/chat/messages',
            success: function(data) {
                parseShouts(data);
            }
        });
    }

    window.updatePrivateShouts = function() {
        $.ajax({
            type: "GET",
            url: '/chat/messages/private/' + private_user,
            success: function(data) {
                parseShouts(data, 'private_' + private_user);
            }
        });
    }

    window.parseShouts = function(shouts, context = 'shoutbox') {
        $("#" + context).empty();

        if (shouts === 'banned') {
            var div = $("<div>", {
                text: 'You have been banned from the shoutbox.'
            });
            $("#shoutbox").append(div);
        } else {
            for (var shout in shouts) {
                shout = shouts[shout];
                var parsed = formatShout(shout);
                var div = $("<div>", {
                    id: shout.id,
                    html: parsed
                });
                if (shout.can_edit) div.attr('ondblclick', 'modifyShout(this)');
                $("#" + context).append(div);
            }
        }
    }

    window.modifyShout = function(elem) {
        var msg = $(elem).find("#message");
        let text = msg.html();
        var imgs = $($(msg).find("img").get().reverse()).each(function(index) {
            let title = $(this).attr('title');
            let smiley = smileys[title];
            imgText = $(this).prop('outerHTML');
            text = text.replace(imgText, smiley.replace);

        });
        text = turndownService.turndown(text);
        editing_shout = elem.id;
        $("#new_message").val(text);
        $("#modify_shout").show();
    }

    window.editShout = function() {
        $.ajax({
            type: "PATCH",
            url: '/chat/message/' + editing_shout,
            headers: {
                'X-CSRF-TOKEN': csrf
            },
            data: {
                message: $("#new_message").val()
            },
            success: function(data) {
                cancelModifyShout();
                updateShouts(data);
            }
        });
    }

    window.deleteShout = function() {
        $.ajax({
            type: "DELETE",
            url: '/chat/message/' + editing_shout,
            headers: {
                'X-CSRF-TOKEN': csrf
            },
            success: function(data) {
                cancelModifyShout();
                updateShouts(data);
            }
        });
    }

    window.cancelModifyShout = function() {
        $("#new_message").val();
        $("#modify_shout").hide();
        editing_shout = -1;
    }


    window.fetchSmileys = function() {
        $.ajax({
            type: "GET",
            url: '/chat/smileys',
            success: function(data) {
                for (var smiley in data) {
                    smiley = data[smiley];
                    smileys[smiley.name] = smiley;
                }
            }
        });
    }

    window.openPrivate = function(id, name) {
        if ($("#private_" + id).length) {
            $('#tab_' + id).tab('show');
        } else {
            // Make tab
            let tab = $("<a>", {
                class: 'nav-link active',
                href: '#private_' + id,
                id: 'tab_' + id,
                role: 'tab',
                text: name,
                'data-toggle': 'tab',
                'aria-controls': 'private_' + id
            });

            $('.nav-link.active').removeClass('active');

            $('#shoutboxTabs').append($("<li>", {
                class: 'nav-item'
            }).append(tab));

            // Make pane
            let pane = $("<div>", {
                class: 'tab-pane fade show',
                id: 'private_' + id,
                role: 'tabpanel',
                'aria-labelledby': 'tab_' + id
            });

            // Switch active panel
            $('.tab-pane.active').removeClass('active');
            pane.addClass('active');

            $('#shoutboxTabsContent').append(pane);
        }

        private_user = id;

        updatePrivateShouts();
    }

    window.formatShout = function(shout) {
        let username = formatUsernameStyle(shout.user);
        shout.message = parseSmileys(shout.message);
        if (shout.sys) {
            return `*[SYS] ${username} <span id="message">${shout.message}</span>*`;
        } else {
            let utcTime = moment.utc(shout.created_at).toDate();
            let time = moment(utcTime).local();
            let formattedTime = moment(time).fromNow();
            let styleProps = formatShoutStyle(shout.user.shout_style);
            return `[${formattedTime}] ${username}: <span id="message" style="${styleProps}">${shout.message}</span>`;
        }
    }

    window.parseSmileys = function(message) {
        if (smileys.length == 0) return message;
        for (var smiley in smileys) {
            smiley = smileys[smiley];
            message = message.replace(new RegExp(smiley.replace,"g"), `<img src='${smiley.image}' title='${smiley.name}' />`)
        }

        return message;
    }

    window.formatUsernameStyle = function(user) {
        return `<span id="user" onclick="openPrivate(${user.id}, '${user.name}')">${user.usergroup.markup_before}${user.name}${user.usergroup.markup_after}</span>`;
    }

    window.formatShoutStyle = function(style) {
        if (! style) return;
        
        let props = [];

        if (style.color != '') props.push('color: ' + style.color);
        if (style.font != '') props.push('font-family: ' + style.font);
        if (style.bold) props.push('font-weight: bold');
        if (style.italic) props.push('font-style: italic');
        if (style.underline) props.push('text-decoration: underline');

        return props.join('; ');
    }

    window.submit = function () {
        $.ajax({
            type: "POST",
            url: '/chat/submit',
            headers: {
                'X-CSRF-TOKEN': csrf
            },
            data: {
                message: $("#message").val(),
                private: private_user
            },
            success: function(data) {
                $("#message").val('');
                updateShouts(data);
            }
        });
    }

    fetchSmileys();
    newShouts();
    
    setInterval(function () {
        newShouts();
    }, 3000);


    $('#message').keypress(function (e) {
        if (e.which == 13) {
            submit();
        }
    });

    $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        let id = e.target.id;
        if (id == 'shoutbox-tab') {
            private_user = -1;
        } else {
            let current_context = e.target.id.split('_')[1];
            private_user = current_context;
            updatePrivateShouts();
        }
    })
}