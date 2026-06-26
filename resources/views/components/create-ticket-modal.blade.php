{{-- CREATE TICKET MODAL --}}
<div id="createModal" class="modal-overlay">
    <div class="modal-box">
        <h3>🎫 Create New Ticket</h3>
        <form action="{{ route('ticketsystem.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Description *</label>
                <textarea name="description" rows="3" required
                    placeholder="Describe your issue..."></textarea>
            </div>

            <div class="form-group">
                <label>App *</label>
                <select name="category" id="appSelect" required onchange="showAutoAssign()">
                    <option value="">-- Select App --</option>
                    @foreach(\App\Models\TicketSupportTeam::APPS as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
                <div class="auto-assign-info" id="autoAssignInfo">
                    <i class="fas fa-user-check"></i>
                    <span id="autoAssignText">This ticket will be auto-assigned to the support member for this app.</span>
                </div>
            </div>

            <div class="form-group">
                <label>Priority *</label>
                <select name="priority" required>
                    <option value="">-- Select Priority --</option>
                    @foreach($priorities as $p)
                        <option value="{{ $p->value }}">{{ $p->label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Attach File <span style="color:#a0aec0;font-weight:400;">(optional)</span></label>
                <div class="file-upload-area" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Click to upload a file</p>
                    <span>PNG, JPG, PDF, DOC up to 10MB</span>
                    <div class="file-name" id="fileName"></div>
                </div>
                <input type="file" id="fileInput" name="attachment"
                       accept=".png,.jpg,.jpeg,.pdf,.doc,.docx"
                       style="display:none"
                       onchange="document.getElementById('fileName').textContent = this.files[0]?.name || ''">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel"
                    onclick="document.getElementById('createModal').style.display='none'">
                    Cancel
                </button>
                <button type="submit" class="btn-submit">
                    Submit Ticket
                </button>
            </div>
        </form>
    </div>
</div>
