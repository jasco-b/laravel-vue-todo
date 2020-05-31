<template>
    <div>
        <div class="alert alert-danger" v-if="hasErrors">
            <p>There was an error, unable to sign in with those credentials.</p>
        </div>
        <form autocomplete="off" @submit.prevent="login" method="post">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email"
                       required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" class="form-control" v-model="password" required>
            </div>
            <button type="submit" class="btn btn-success">Sign in</button>
        </form>
    </div>
</template>

<script>
    import {mapGetters, mapActions} from 'vuex'

    export default {
        name: "Login",
        data() {
            return {
                email: '',
                password: '',
            }
        },
        methods: {
            ...mapActions({
                loginReq:'login'
            }),
            login() {
                this.loginReq({
                    email: this.email,
                    password: this.password,
                }).then(()=>{
                    if (this.$route.query.redirect){
                        this.$router.push(this.$route.query.redirect);
                        return;
                    }
                    this.$router.push('/todos');
                });
                // this.$store.dispatch('login', {});
            }
        },
        computed:{
            hasErrors(){
                return !!Object.keys(this.$store.state.auth.error || {}).length;
            }
        }
    }
</script>

<style scoped>

</style>
