<template>
    <div>
        <div class="card-header">
            Events List
            <button style="float: right"
                    type="button" @click="logout"
                    class="btn btn-primary btn-sm">logout
            </button>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Status</th>
                    <th scope="col">Description</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="event in events">
                    <td>{{ event.id }}</td>
                    <td>{{ event.name }}</td>
                    <td>{{ event.status }}</td>
                    <td>{{ event.description }}</td>
                    <td>
                        <button v-if="event.status === 'pending'"
                                type="button" @click="approveEvent(event.id)"
                                class="btn btn-primary btn-sm">approve
                        </button>
                        <button v-else
                                type="button" disabled
                                class="btn btn-success btn-sm">done
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    name: "EventsListComponent",

    data() {
        return {
            events: [],
        }
    },
    mounted() {
        let token = localStorage.getItem('auth-token');
        axios.get('/api/events', {
            headers: {'Authorization': 'Bearer ' + token}
        }).then(response => {
            this.events = response.data.data;
        }).catch(error => {
            console.log(error)
        });
    },
    methods: {
        approveEvent($id) {
            let token = localStorage.getItem('auth-token');
            axios.get('/api/events/approve/' + $id, {
                headers: {'Authorization': 'Bearer ' + token}
            }).then(response => {
                this.events = response.data.data;
            }).catch(error => {
                console.log(error)
            });
        },
        logout() {
            let token = localStorage.getItem('auth-token');
            axios.get('/api/auth/logout', {
                headers: {'Authorization': 'Bearer ' + token}
            }).then(response => {
                localStorage.removeItem('auth-token');
                localStorage.removeItem('is-admin');
                this.$router.push('/admin/login');
                console.log('loged out')
            }).catch(error => {
                console.log(error)
            });
        }
    }
}
</script>

<style scoped>

</style>
