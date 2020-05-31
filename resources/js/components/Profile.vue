<template>
    <div class="profile">
        <form autocomplete="off" @submit.prevent="updateProfile" method="post">
            <div class="form-group">
                <label for="exampleInputName">Name</label>
                <input type="text" class="form-control" id="exampleInputName" placeholder="Name"
                       name="name" v-model="profile.name">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" autocomplete="off"
                       placeholder="Enter email" v-model="profile.email" name="email">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1"
                       placeholder="Password" v-model="profile.password" autocomplete="new-password"
                       name="new-password">
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        name: "Profile",
        data: function () {
            return {
                profile: {
                    email: '',
                    name: '',
                    password: '',
                }
            }
        },
        methods: {
            updateProfile() {
                this.$store.dispatch('editProfile', this.profile);
            }
        },
        mounted() {
            this.$store.dispatch('profile').then(res => {
                this.profile = {...res.data.data, password: ''};
            });
        }
    }
</script>

<style scoped>

</style>
