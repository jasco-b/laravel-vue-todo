<template>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <router-link to="/" class="navbar-brand">Todo app</router-link>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
                aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">

                <router-link class="nav-item"  :to="{name: 'home'}" exact="/">
                    <a class="nav-link">Home</a>
                </router-link>
                <router-link class="nav-item" v-if="isAuth" :to="{name: 'todos'}">
                    <a  class="nav-link">Todos</a>
                </router-link>
                <li class="nav-item" v-if="isAuth">
                    <a href="#" class="nav-link" v-on:click="logout">Logout</a>
                </li>
                <router-link class="nav-item" v-if="isAuth"  :to="{name: 'profile'}">
                    <a  class="nav-link">{{ userName }}</a>
                </router-link>
                <router-link class="nav-item" v-if="!isAuth" :to="{name: 'login'}">
                    <a  class="nav-link">Login</a>
                </router-link>
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
