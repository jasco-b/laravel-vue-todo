<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <form class="form-inline" @submit.prevent="addNew">
                    <div class="form-group mx-sm-3 mb-2">
                        <label for="inputPassword2" class="sr-only">Todo</label>
                        <input type="text" class="form-control" id="inputPassword2"
                               placeholder="Todo" v-model="todo" required>
                    </div>
                    <button type="submit" class="btn btn-success mb-2">Add Todo</button>
                </form>
            </div>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item" :class="{'line-througt': todo.status }" v-for="todo in todos" :key="todo.id"
                v-on:click="complete(todo)">
                {{ todo.task}}
            </li>

        </ul>
    </div>
</template>

<script>
    export default {
        name: "Todo.vue",
        data: function () {
            return {
                todo: '',
            }
        },
        methods: {
            complete(todo) {
                this.$store.dispatch('completeTodo', todo);
            },
            addNew() {
                this.$store.dispatch('newTodo', {
                    task: this.todo,
                }).then(()=>{
                    this.todo = '';
                });
            }
        },
        computed: {
            todos() {
                return this.$store.getters.todos;
            }
        },
        mounted() {
            this.$store.getters.profile.id
                ? this.$store.dispatch('todos')
                : this.$store.dispatch('profile').then(
                    () => this.$store.dispatch('todos'));
        }
    }
</script>

<style scoped>
    .line-througt {
        text-decoration: line-through;
    }
</style>
