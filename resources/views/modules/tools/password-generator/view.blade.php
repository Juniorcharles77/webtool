@push('alpine-components')
    <script type="text/javascript">
        window.bitflanPasswordGeneratorComponent = function() {
            return {
                length: 16,

                incSymbols: true,
                incNumbers: true,
                incLowercase: true,
                incUppercase: true,
                incAmbiguous: true,

                generate() {
                    let list = '';

                    if(this.incSymbols)
                        list += '@#$%';
                    
                    if(this.incNumbers)
                        list += '0123456789';

                    if(this.incLowercase)
                        list += 'abcdefghijklmnopqrstuvwxyz';

                    if(this.incUppercase)
                        list += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

                    if(this.excAmbiguous)
                        list += '(){}[]/\\\'"`~,;:.<>';
                
                    let password = '';

                    for(let i = 0; i < this.length; i++) {
                        password += list[Math.floor(Math.random() * ((list.length - 1) - 0 + 1) + 0)];
                    }

                    this.$refs.password.value = password;
                }
            };
        }
    </script>
@endpush

<div x-data="window.bitflanPasswordGeneratorComponent()">
    <div class="form-group">
        <label for="" class="custom-label">{{ trans('webtools/tools/password-generator.length') }}</label>
        <input type="number" class="custom-input" value="16" x-model="length" placeholder="e.g 12" max="100" min="1">
    </div>
    <hr class="small-marg">
    <div class="row">
        <div class="col-xl-3 col-lg-4 col-md-6 col-12">
            <div class="form-group">
                <label for="" class="custom-label">{{ trans('webtools/tools/password-generator.symbols') }}</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" x-model="incSymbols" checked role="switch" id="switch-1">
                    <label class="form-check-label" for="switch-1">( e.g. @#$% )</label>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-12">
            <div class="form-group">
                <label for="" class="custom-label">{{ trans('webtools/tools/password-generator.numbers') }}</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" x-model="incNumbers" checked role="switch" id="switch-2">
                    <label class="form-check-label" for="switch-2">( e.g. 123456 )</label>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-12">
            <div class="form-group">
                <label for="" class="custom-label">{{ trans('webtools/tools/password-generator.lowercase') }}</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" x-model="incLowercase" checked role="switch" id="switch-3">
                    <label class="form-check-label" for="switch-3">( e.g. abcdefgh )</label>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-12">
            <div class="form-group">
                <label for="" class="custom-label">{{ trans('webtools/tools/password-generator.uppercase') }}</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" x-model="incUppercase" checked role="switch" id="switch-4">
                    <label class="form-check-label" for="switch-4">( e.g. ABCDEFGH )</label>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-12">
            <div class="form-group">
                <label for="" class="custom-label">{{ trans('webtools/tools/password-generator.ambiguous') }}</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" x-model="incAmbiguous" checked role="switch" id="switch-6">
                    <label class="form-check-label" for="switch-6">( { } [ ] ( ) / \ ' " ` ~ , ; : . < > )</label>
                </div>
            </div>
        </div>
    </div>
    <button class="mb-5 btn custom--btn button__lg" @click="generate()">{{ trans('webtools/tools/password-generator.submit') }}</button>
    <div class="mb-5 form-group">
        <label for="" class="custom-label">{{ trans('webtools/tools/password-generator.result-label') }}</label>
        <div class="copy-input">
            <input x-ref="password" type="text" class="custom-input" value="{{ trans('webtools/tools/password-generator.result-placeholder') }}">
            <button class="btn custom--btn button__md copy-btn btn__dark" @click="window.writeClipboardText($event, $refs.password.value)">{{ trans('webtools/general.copy') }}</button>
        </div>
    </div>
</div>