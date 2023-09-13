<div class="modal fade" id="addTemplate" tabindex="-1" aria-labelledby="addTemplateLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold" id="addTemplateLabel">Add Template</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('template.save') }}" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                @csrf
                <div class="modal-body">
                    <div class="col-lg-12">
                        <div class="mb-3 row">
                            <label for="validationAlias" class="col-lg-1 col-form-label fw-bold">Alias</label>
                            <div class="col-lg-11">
                                <input type="text" class="form-control" id="validationAlias" name="alias" value="{{ old('alias') }}"
                                       aria-describedby="validationAliasHelp validationAliasFeedback" placeholder="e.g. Simple Template or Event Template" required>
                                <div id="validationAliasHelp" class="form-text">An alias for the template. (Only an information for you.)</div>
                            </div>
                            <div class="valid-feedback">{{ __("Looks good!") }}</div>
                            <div id="validationAliasFeedback" class="invalid-feedback">{{ __("Please provide a valid alias.") }}</div>
                        </div>
                        <div class="mb-3 row">
                            <label for="validationFile" class="col-lg-1 col-form-label fw-bold">Template</label>
                            <div class="col-lg-11">
                                <input type="file" class="form-control" id="validationFile" accept="image/png, image/jpeg" name="file"
                                       aria-describedby="validationFileHelp validationFileFeedback" value="{{ old('file') }}" required>
                                <div id="validationFileHelp" class="form-text">The template file. (min. 468x60px (Width x Height), max. 1024x300px (Width x Height), PNG/JPEG, max. 5 MB)</div>
                            </div>
                            <div class="valid-feedback">{{ __("Looks good!") }}</div>
                            <div id="validationFileFeedback" class="invalid-feedback">{{ __("Please provide a valid file.") }}</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
            @include('inc.form-validation')
        </div>
    </div>
</div>
