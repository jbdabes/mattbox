<div class="modal fade" id="shoutStyleEditorModal" tabindex="-1" role="dialog" aria-labelledby="shoutStyleEditorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shoutStyleEditorModalLabel">Edit Shout Style</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-2 col-form-label">Font</label>
                                <div class="col-sm-10">
                                    <select id="styleEditFont" class="form-control">
                                        <option value="">Default</option>
                                        @foreach ($allowedFonts as $font)
                                            <option value="{{ $font }}">{{ $font }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            
                        </div>
                    </div>
                </form>
            </div>
      
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>