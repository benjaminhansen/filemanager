<template>
    <div>
        <div class="row">
            <div class="col-md-4">
                <div class="row p-3" v-for="user in department.users">
                    <div class="col-md-6">
                        <span class="btn">{{ user.user.fname }} {{ user.user.lname }}</span>
                    </div>
                    <div class="col-md-5">
                        <select class="form-control" :data-userid="user.user.id" disabled>
                            <option v-for="permission in permissions" :value="permission.id" :selected="permission.id == user.permission.id">{{ permission.name }}</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger" @click="deleteUser(user.user.id)">Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="new-user-modal">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                  <div class="form-group">
                      <label>User's username</label>
                      <input type="text" class="form-control" v-model="newuser.username">
                  </div>
                  <div class="form-group">
                      <label>Permission</label>
                      <select class="form-control" v-model="newuser.permissionid">
                          <option v-for="permission in permissions" :value="permission.id">{{ permission.name }}</option>
                      </select>
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
                department:{},
                permissions:[],
                newuser:{
                    username:"",
                    permissionid:0
                }
            };
        },

        props: ['departmentid'],

        methods: {
            prepare() {
                this.getDepartment();
                this.getPermissions();
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

            getPermissions() {
                axios.get(window.Laravel.app_url + '/api/v1/get/permissions')
                    .then(response => {
                        this.permissions = response.data;
                    });
            },

            updatePermission(e) {
                var userid = e.target.attributes['data-userid'].value;
                var permissionid = e.target.value;

                axios.post(window.Laravel.app_url + '/api/v1/post/update-user-permission', {
                    userid:userid,
                    permissionid:permissionid,
                    departmentid:this.department.id
                }).then(response => {
                    this.getDepartment();
                });
            },

            addNewUser() {
                $("#new-user-modal").modal();
            },

            createNewUser() {
                if(this.newuser.username.trim() && this.newuser.permissionid > 0) {
                    axios.post(window.Laravel.app_url + '/api/v1/post/add-new-user', {
                        username:this.newuser.username.trim(),
                        permissionid:this.newuser.permissionid,
                        departmentid:this.department.id
                    }).then(response => {
                        if(response.data.success) {
                            this.getDepartment();
                            $('#new-user-modal').modal('hide');
                            this.cancelNewUser();
                        } else {
                            alert(response.data.message);
                        }
                    });
                } else {
                    alert("A username and permission level are required!");
                }
            },

            cancelNewUser() {
                this.newuser = {
                    username:"",
                    permissionid:0
                };
            },

            deleteUser(userid) {
                if(confirm("Are you sure?")) {
                    axios.post(window.Laravel.app_url + '/api/v1/post/delete-user-permission', {
                        userid:userid,
                        departmentid:this.department.id
                    }).then(response => {
                        if(response.data.success) {
                            this.getDepartment();
                        } else {
                            alert(response.data.message);
                        }
                    });
                }
            }
        }
    }
</script>
