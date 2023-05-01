@push('alpine-components')
    <script>
        window.bitflanConvertComponent = function() {
            return {
                generate() {
                    let canvas = this.$refs.canvas;
                    let ctx    = canvas.getContext('2d');
                    let a      = this.$refs.anchor;

                    var reader = new FileReader();
                    reader.onload = function(event){
                        var img = new Image();
                        img.onload = () => {
                            canvas.width = img.width;
                            canvas.height = img.height;
                            ctx.drawImage(img,0,0);

                            a.href = canvas.toDataURL('image/png', 1);
                            a.click();
                        }
                        img.src = event.target.result;
                    }
                    reader.readAsDataURL(this.$refs.upload.files[0]); 
                }
            }
        }
    </script>
@endpush

<div x-data="window.bitflanConvertComponent()">
    <div class="form-group">
        <label class="custom-label">{{ trans('webtools/tools/webp-to-png.image') }}</label>
        <input type="file" x-ref="upload" class="form-control" accept="image/png,image/jpeg,image/jpg,image/webp,image/svg" />
    </div>
    <div class="form-group">
        <button @click="generate()" class="btn custom--btn button__lg">{{ trans('webtools/tools/webp-to-png.submit') }}</button>
    </div>

    <div class="d-none">
        <canvas x-ref="canvas"></canvas>
        <a href="#" x-ref="anchor" download="image.png"></a>
    </div>
</div>