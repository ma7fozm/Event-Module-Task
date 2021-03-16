<template>
    <div>
        <div class="card-header">
            Create Event
            <button style="float: right"
                    type="button" @click="logout"
                    class="btn btn-primary btn-sm">logout
            </button>
        </div>
        <div class="card-body">
            <form @submit.prevent="submit">
                <div class="alert alert-success" v-show="success">Event created successfully.</div>
                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">Event Name</label>
                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control" name="name"
                               v-model="fields.name" autofocus>
                        <span v-if="errors && errors.name" :class="'invalid-feedback-cls'">
                            <strong>{{ errors.name[0] }}</strong>
                        </span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="status" class="col-md-4 col-form-label text-md-right">Event Status</label>
                    <div class="col-md-6">
                        <select class="form-control" id="status" name="status" v-model="fields.status">
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="description" class="col-md-4 col-form-label text-md-right">Event Description</label>
                    <div class="col-md-6">
               <textarea id="description" rows="5" class="form-control" name="description"
                         v-model="fields.description"></textarea>
                        <span v-if="errors && errors.description" :class="'invalid-feedback-cls'">
                            <strong>{{ errors.description[0] }}</strong>
                        </span>
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-8 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            Create Event
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            fields: {
                name: '',
                status: 'pending',
                description: '',
            },
            success: false,
            errors: {}
        }
    },
    methods: {
        submit() {
            let token = localStorage.getItem('auth-token');
            axios.post('/api/events/create', this.fields, {
                headers: {'Authorization': 'Bearer ' + token}
            }).then(response => {
                this.handleApiResponse(response);
            }).catch(error => {
                if (error.response.status === 422) this.errors = error.response.data.error;
                else if (error.response.status === 401) {
                    this.error_message = error.response.data.error;
                    this.error = true;
                }
                this.success = false;
            });
        },
        handleApiResponse(response) {
            console.log(response.data.data);
            // reset assets
            this.success_message = response.data.message;
            this.fields = {};
            this.success = true;
            this.error = false;
            this.errors = {};
        },
        logout(){
            let token = localStorage.getItem('auth-token');
            axios.get('/api/auth/logout', {
                headers: {'Authorization': 'Bearer ' + token}
            }).then(response => {
                localStorage.removeItem('auth-token');
                localStorage.removeItem('is-admin');
                this.$router.push('/login');
                console.log('loged out')
            }).catch(error => {
                console.log(error)
            });
        }
    }
}
</script>

<style scoped>
.invalid-feedback-cls {
    width: 100%;
    margin-top: 0.25rem;
    font-size: 80%;
    color: #e3342f;
}
</style>
