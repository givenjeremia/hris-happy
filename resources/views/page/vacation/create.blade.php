<div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Create Vacation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-create">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="text" name="date" class="form-control" id="date-vacation" placeholder="YYYY-MM-DD - YYYY-MM-DD" required>
                    </div>
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" name="subject" class="form-control" placeholder="Enter Subject" required>
                    </div>
                    <div class="form-group">
                        <label>Information</label>
                        <textarea name="information" class="form-control" placeholder="Enter Information" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="btn-save-create" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
