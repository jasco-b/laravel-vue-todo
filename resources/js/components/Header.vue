<template>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <router-link to="/" class="navbar-brand">Todo app</router-link>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
                aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">

                <li class="nav-item active">
                    <router-link to="/" class="nav-link">Home</router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/todos" class="nav-link">Todos</router-link>
                </li>
                <li class="nav-item" v-if="isAuth">
                    <a href="#" class="nav-link" v-on:click="logout">Logout</a>
                </li>
                <li class="nav-item" v-if="isAuth">
                    <router-link to="/profile" class="nav-link">{{ userName}}</router-link>
                </li>
                <li class="nav-item" v-if="!isAuth">
                    <router-link to="/login" class="nav-link">Login</router-link>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script>
    export default {
        name: "header.vue",
        computed: {
            userName() {
                return this.$store.getters.profileName;
            },
            isAuth() {
                return this.$store.getters.isAuth;
            },
            test() {
                return this.$store.getters.isAuth;
            }
        },
        methods:{
            logout(){
                this.$store.dispatch('logout');
                this.$router.push('/login');
            }
        }
    }
</script>

<style scoped>

</style>
