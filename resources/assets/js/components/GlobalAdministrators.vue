<template>
    <div>
        <div class="row">
            <div class="col-md-4">
                <div class="row p-3" v-for="admin in admins" v-if="admin.user.id != current_user_id">
                    <div class="col-md-5">
                        <span class="btn">{{ admin.user.fname }} {{ admin.user.lname }}</span>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger" @click="deleteUser(admin.user.id)">Delete</button>
                    </div>
                </div>
                <button type="button" class="btn btn-success btn-block" @click="addNewUser">Add New Global Administrator</button>
            </div>
        </div>

        <div class="modal fade" id="new-user-modal">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Global Administrator</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                  <div class="form-group">
                      <label>User's username</label>
                      <input type="text" class="form-control" v-model="newuser.username">
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="cancelNewUser()">Cancel</button>
                <button type="button" class="btn btn-primary" @click="createNewUser()">Submit</button>
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
                admins:[],
                newuser: {
                    username:""
                },
                current_user_id:null
            };
        },

        methods: {
            prepare() {
                this.getAdmins();
                this.setCurrentUserId();
            },

            setCurrentUserId() {
                this.current_user_id = window.Laravel.current_user_id;
            },

            getAdmins() {
                axios.get(window.Laravel.app_url + '/api/v1/get/global-administrators')
                    .then(response => {
                        this.admins = response.data;
                    });
            },

            deleteUser(userid) {
                if(confirm("Are you sure?")) {
                    axios.post(window.Laravel.app_url + '/api/v1/post/delete-global-administrator', {
                        userid:userid
                    }).then(response => {
                        this.getAdmins();
                    });
                }
            },

            addNewUser() {
                $("#new-user-modal").modal();
            },

            cancelNewUser() {
                this.newuser = {
                    username:""
                };
            },

            createNewUser() {
                if(this.newuser.username.trim()) {
                    axios.post(window.Laravel.app_url + '/api/v1/post/add-global-administrator', {
                        username:this.newuser.username
                    }).then(response => {
                        if(response.data.success) {
                            this.getAdmins();
                            $('#new-user-modal').modal('hide');
                            this.cancelNewUser();
                        } else {
                            alert(response.data.message);
                        }
                    });
                } else {
                    alert("A username is required!");
                }
            }
        }
    }
</script>
