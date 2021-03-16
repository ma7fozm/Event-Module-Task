<template>
    <div>
        <div class="card-header">Admin Login</div>
        <div class="card-body">
            <form @submit.prevent="submit">
                <div class="alert alert-success" v-show="success">{{ success_message }}</div>
                <div class="alert alert-danger" v-show="error">{{ error_message }}</div>
                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                    <div class="col-md-6">
                        <input id="email" class="form-control "
                               name="email" v-model="fields.email" autocomplete="email" autofocus>
                        <span v-if="errors && errors.email" :class="'invalid-feedback-cls'">
                            <strong>{{ errors.email[0] }}</strong>
                        </span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control"
                               name="password" v-model="fields.password" autocomplete="current-password">
                        <span v-if="errors && errors.password" :class="'invalid-feedback-cls'" role="alert">
                            <strong> {{ errors.password[0] }}</strong>
                        </span>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   v-model="fields.remember_me" name="remember" id="remember">
                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-8 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            Login
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    name: "LoginComponent",
    data() {
        return {
            success_message: '',
            error_message: '',
            fields: {
                email: '',
                password: '',
                remember_me: false,
            },
            success: false,
            error: false,
            errors: {}
        }
    },
    methods: {
        submit() {
            axios.post('/api/auth/admin/login', this.fields).then(response => {
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

            // reset assets
            this.success_message = response.data.message;
            this.fields = {};
            this.success = true;
            this.error = false;
            this.errors = {};

            // save auth token to localstorage
            localStorage.setItem('auth-token', response.data.data.token);
            localStorage.setItem('is-admin', response.data.data.is_admin);

            // redirect to create event component
            this.$router.push('/events');
        },
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
