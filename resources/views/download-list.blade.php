<div class="container">
    <div class="row">
        <div class="col">
            <h3 class="text-center">Downloads list
                <button class="btn btn-primary btn-sm" @click="getFilesList">
                    <i class="fa fa-refresh"></i>
                </button></h3>
        </div>
    </div>
    <div class="row">
        <div class="offset-1 col-10 mt-3">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Filename</th>
                        <th scope="col">Url</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="file in download_list">
                        <th scope="row">@{{ file.id }}</th>
                        <td>
                            <a :href="'/get-file/' + file.id">@{{ file.original_file_name }}</a>
                        </td>
                        <td><a :href="file.file_url">@{{ file.file_url }}</a></td>
                        <td>
                            <span v-if="file.file_status === 'complete'" class="badge badge-success">Complete</span>
                            <span v-if="file.file_status === 'pending'" class="badge badge-secondary">Pending</span>
                            <span v-if="file.file_status === 'error'" class="badge badge-danger">Error</span>
                            <span v-if="file.file_status === 'not_valid'" class="badge badge-danger">File not valid</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('footer-script')
    <script>
        mixins.push({
            data: () => ({
                download_list : [],
            }),
            methods : {
                getFilesList(){
                    axios
                        .get('{{route('get-files')}}')
                        .then( (response) =>{
                            this.download_list = response.data;
                        });
                }
            },
            mounted() {
                this.getFilesList();
                // setInterval(()=>{
                //     this.download_list = [];
                //     this.getFilesList();
                // }, 5000);
            }
        });
    </script>
@endpush
