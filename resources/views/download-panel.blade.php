<div class="container">
    <div class="row">
        <div class="col">
            <h3 class="text-center mt-4">Download Scheduler</h3>
        </div>
    </div>
    <div class="row">
        <div class="offset-3 col-6 mt-3">
            <form>
                <div class="input-group input-group-lg">
                    <input type="text" class="form-control" placeholder="Paste download url" aria-label="Paste download url" v-model="download_url">
                    <div class="input-group-append">
                        <button class="btn btn-success" @click="addToDownload" type="button" v-html="download_btn_text"></button>
                    </div>
                </div>
                @{{ download_status  }}
            </form>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<hr>
@push('footer-script')
    <script>
        mixins.push({
            data: () => ({
                download_url : null,
                download_btn_text : 'Download',
                download_status : null,
            }),
            methods : {
                addToDownload(){
                    this.download_status = 'Pending';
                    if ( this.download_url !== null && this.download_url.trim() !== '' )
                    {
                        let isUrl = this.validURL( this.download_url );

                        if ( !isUrl )
                            this.download_status = 'Url is not valid';

                        this.download_btn_text = '<i class="fa fa-spinner fa-spin"></i>';

                        axios
                            .post('{{ route('download-link') }}', { url: this.download_url })
                            .then(( response ) => {
                                this.download_status = response.data;
                                this.download_btn_text = 'Download';
                                this.getFilesList();
                            })
                            .catch( function ( error ) {
                                this.download_status = error;
                            });
                    }
                    else
                    {
                        this.download_status = 'Url is empty';
                    }

                },
                validURL(str) {
                    let pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
                        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
                        '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
                        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
                        '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
                        '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
                    return !!pattern.test(str);
                }
            },
            watch : {
                download_status: function(newVal, oldVal) {
                    setTimeout(()=>{
                        if ( newVal !== null )
                            this.download_status = null;
                    },3000);
                }
            }
        })
    </script>
@endpush
