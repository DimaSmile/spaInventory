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
                <v-btn :to="{ name: 'home' }" flat>home</v-btn>
                <v-btn v-if="!$auth.check()" :to="{ name: 'login' }" flat>Login</v-btn>
                <v-btn v-if="!$auth.check()" :to="{ name: 'register' }" flat>Register</v-btn>
                <v-btn v-if="$auth.check()" @click.prevent="$auth.logout()" flat>Logout</v-btn>
            </v-toolbar-items>
        </v-toolbar>
    </div>
    <!-- <div class="panel panel-default">
        <div class="panel-heading">
            <nav>
                <ul class="list-inline">
                    <li>
                        <router-link :to="{ name: 'home' }">Home</router-link>
                    </li>
                    <li v-if="!$auth.check()" class="pull-right">
                        <router-link :to="{ name: 'login' }">Login</router-link>
                    </li>
                    <li v-if="!$auth.check()" class="pull-right">
                        <router-link :to="{ name: 'register' }">Register</router-link>
                    </li>
                    <li v-if="$auth.check()" class="pull-right">
                        <a href="#" @click.prevent="$auth.logout()">Logout</a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="panel-body">
            <router-view></router-view>
        </div>
    </div> -->
</template>

<script>
    export default {
        
    }
</script>

<style scoped>

</style>