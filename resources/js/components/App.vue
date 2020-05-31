<template>
    <div>

        <Header/>

        <main role="main" class="container pt-5">

            <router-view/>

        </main><!-- /.container -->

    </div>
</template>

<script>
    export default {
        name: "Home.vue",
        created() {
            this.$store.dispatch('profile');
        }
    }
</script>

<style scoped>

</style>
