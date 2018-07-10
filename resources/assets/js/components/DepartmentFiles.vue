<template>
    <div>
        <p v-if="can_upload"><button type="button" class="btn btn-success" @click="uploadNewFiles">Upload New File(s)</button></p>
        <p>
            <label v-for="type in display_types" class="display-type-button">
                <input type="radio" v-model="display_type" :value="type"> {{ type }}
            </label>
        </p>
        <div v-if="display_type == 'List'">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>File Name</th>
                            <th>Uploaded</th>
                            <th>Uploaded By</th>
                            <th>File Type</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="file in department.files">
                            <td>{{ file.name }}</td>
                            <td>{{ file.created_at }}</td>
                            <td>{{ file.uploader.fname }} {{ file.uploader.lname }}</td>
                            <td>{{ file.mime }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" :href="file.full_url" data-fancybox="preview-file">Preview</button>
                                <button type="button" class="btn btn-sm btn-success" v-clipboard:copy="file.full_url" v-clipboard:success="onCopy" v-clipboard:error="onError">Get URL</button>
                            </td>
                            <td><button type="button" class="btn btn-sm btn-danger" @click="deleteFile(file.id)" v-if="can_upload">Delete</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div v-else>
            <div class="row">
                <div class="col-md-3 gallery-col" v-for="file in department.files">
                    <div class="card">
                        <div class="card-image-header" :style="'background-image:url('+file.preview_url+');'"></div>
                        <div class="card-body">
                            <h5 class="card-title">{{ file.name }}</h5>
                            <p class="text-muted">
                                <strong>Uploaded At</strong>: {{ file.created_at }}<br />
                                <strong>Uploaded By</strong>: {{ file.uploader.fname }} {{ file.uploader.lname }}<br />
                                <strong>File Type</strong>: {{ file.mime }}
                            </p>
                            <p>
                                <button type="button" class="btn btn-sm btn-primary" :href="file.full_url" data-fancybox="preview-file">Preview</button>
                                <button type="button" class="btn btn-sm btn-success" v-clipboard:copy="file.full_url" v-clipboard:success="onCopy" v-clipboard:error="onError">Get URL</button>
                                <button type="button" class="btn btn-sm btn-danger" @click="deleteFile(file.id)" v-if="can_upload">Delete</button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="new-file-modal" v-if="can_upload">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Upload New File(s)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                  <div class="form-group">
                      <label>Choose file(s)</label><br />
                      <input type="file" multiple id="attachments" @change="uploadFieldChange">
                  </div>
                  <div class="form-group">
                        <div class="attachment-holder animated fadeIn" v-cloak v-for="(attachment, index) in attachments">
                          <span class="label label-primary">{{ attachment.name + ' (' + Number((attachment.size / 1024 / 1024).toFixed(1)) + 'MB)'}}</span>
                          <span class="" style="background: red; cursor: pointer;" @click="removeAttachment(attachment)"><button class="btn btn-sm btn-danger">Remove</button></span>
                        </div>
                  </div>
                    <div class="progress" v-if="uploading">
                        <div class="progress-bar" role="progressbar" :style="'width: '+percentCompleted+'%'"></div>
                    </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="cancelNewFile()">Cancel</button>
                <button type="button" class="btn btn-primary" @click="createNewFiles()">Submit</button>
              </div>
            </div>
          </div>
        </div>
    </div>
</template>

<script>
    export default {
        ready() {
            this.prepare();
        },

        mounted() {
            this.prepare();
        },

        data() {
            return {
                department:{},
                display_types: [
                    'List',
                    'Gallery'
                ],
                display_type:"List",
                can_upload:false,
                attachments:[],
                formData: new FormData(),
                percentCompleted: 0,
                errors:{},
                uploading:false
            };
        },

        props: ['departmentid'],

        methods: {
            prepare() {
                this.getDepartment();
                this.checkCurrentUser();
            },

            getDepartment() {
                axios.get(window.Laravel.app_url + '/api/v1/get/department/' + this.departmentid)
                    .then(response => {
                        if(response.data.success) {
                            this.department = response.data.data;
                        } else {
                            alert(response.data.message);
                        }
                    });
            },

            checkCurrentUser() {
                axios.post(window.Laravel.app_url + '/api/v1/post/check-current-user', {
                    departmentid:this.departmentid
                }).then(response => {
                    if(response.data.success) {
                        this.can_upload = true;
                    } else {
                        this.can_upload = false;
                    }
                });
            },

            uploadNewFiles() {
                $("#new-file-modal").modal();
            },

            deleteFile(fileid) {
                if(confirm("Are you sure?") && this.can_upload) {
                    axios.post(window.Laravel.app_url + '/api/v1/post/delete-file', {
                        fileid:fileid,
                        departmentid:this.department.id
                    }).then(response => {
                        this.getDepartment();
                    });
                }
            },

            createNewFiles() {
                this.uploading = true;

                var config = {
                    headers: { 'Content-Type': 'multipart/form-data' } ,
                    onUploadProgress: function(progressEvent) {
                        this.percentCompleted = Math.round( (progressEvent.loaded * 100) / progressEvent.total );
                        this.$forceUpdate();
                    }.bind(this)
                };
                // Make HTTP request to store announcement
                axios.post(window.Laravel.app_url + '/api/v1/post/upload-new-files/' + this.department.id, this.formData, config)
                .then(response => {
                    if (response.data.success) {
                        this.resetData();
                        this.uploading = false;
                        $("#new-file-modal").modal('hide');
                        this.getDepartment();
                    } else {
                        this.errors = response.data.errors;
                    }
                });
            },

            cancelNewFile() {
                this.attachments = [];
            },

            uploadFieldChange(e) {
                var files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;

                for (var i = files.length - 1; i >= 0; i--) {
                    this.attachments.push(files[i]);
                }

                for (var i = 0; i < this.attachments.length; i++) {
                    let attachment = this.attachments[i];
                    this.formData.append('attachments[]', attachment);
                }

                // Reset the form to avoid copying these files multiple times into this.attachments
                document.getElementById("attachments").value = [];
            },

            getAttachmentSize() {
                this.upload_size = 0; // Reset to beginningƒ
                this.attachments.map((item) => { this.upload_size += parseInt(item.size); });

                this.upload_size = Number((this.upload_size).toFixed(1));
                this.$forceUpdate();
            },

            removeAttachment(attachment) {
                this.attachments.splice(this.attachments.indexOf(attachment), 1);
                this.getAttachmentSize();
            },

            resetData() {
                this.formData = new FormData(); // Reset it completely
                this.attachments = [];
            },

            onCopy(e) {
                alert("Copied to clipboard!");
            },

            onError(e) {
                alert("Failed to copy URL.\n\nHere is the full URL that you can copy manually:\n" + e.text);
            }
        }
    }
</script>
