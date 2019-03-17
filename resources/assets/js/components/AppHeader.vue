<template>
    <div>
        <!-- <v-navigation-drawer dark>
            <v-list>
                <v-list-tile avatar>
                    
                </v-list-tile>
            </v-list>
        </v-navigation-drawer> -->
        <v-toolbar app dark class="grey darken-3">
                <v-toolbar-title v-text="'Inventory'"></v-toolbar-title>
            <v-spacer></v-spacer>

            <v-toolbar-items class="hidden-sm-and-down">
                <v-btn v-for="category in categories"
                       :key="category.id"
                       :to="{ name: 'dashboard', params: { category_id: category.id, category_name: category.name }}"
                       flat>
                    {{ category.name }}
                </v-btn>
                <v-btn :to="{ name: 'home' }" flat>home</v-btn>
                <v-btn v-if="!$auth.check()" :to="{ name: 'login' }" flat>Login</v-btn>

                <!-- //if you want enable registration uncomment this block -->
                <!-- <v-btn v-if="!$auth.check()" :to="{ name: 'register' }" flat>Register</v-btn> -->

                <!-- <v-btn v-if="$auth.check()" @click.prevent="$auth.logout()" flat>Logout</v-btn> -->
            </v-toolbar-items>
        </v-toolbar>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                categories: null,
            }
        },
        created () { //inside hook 'created' recommended do fetching data
            this.getCategories();
        },
        methods: {
            getCategories(){
                this.axios.get('categories').then((response) => {
                    this.categories = response.data;
                    // console.log(this.categories);
                    
                })
            }
        }
    }
</script>