<template>
  <div>
    <v-toolbar flat color="white">
      <v-toolbar-title>Текущая категория</v-toolbar-title>
      <!-- <v-divider
        class="mx-2"
        inset
        vertical
      ></v-divider> -->
      <v-spacer></v-spacer>
      <v-dialog v-model="dialog" max-width="500px">
        <template v-slot:activator="{ on }">
          <v-btn color="grey darken-3" dark class="mb-2" v-on="on">Новый продукт</v-btn>
        </template>
        <v-card>
          <v-card-title>
            <span class="headline">{{ formTitle }}</span>
          </v-card-title>

        <v-form ref="form" v-model="valid" lazy-validation>
          <v-card-text>
            <v-container grid-list-md>
                <v-layout wrap>
                    <v-flex xs12>
                        <v-text-field v-model="editedItem.name" label="Наименование" :rules="[v => !!v || 'Name is required']" required></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6>
                        <!-- <v-text-field v-model="editedItem.imageName" hint="Выберите изображение" persistent-hint label="" @click='pickFile' prepend-icon='attach_file'></v-text-field> -->
                            <v-btn
                                :loading="loading3"
                                :disabled="loading3"
                                color="grey darken-3"
                                class="white--text"
                                @click="pickFile"
                                depressed
                            >
                            Загрузить
                            <v-icon right dark>cloud_upload</v-icon>
                            </v-btn>
                            <p class="text-md-left">{{ this.editedItem.imageUrl ? this.editedItem.imageName : 'Выберите изображение'}}</p>
                        <input 
                            type="file"
                            style="display: none"
                            ref="image"
                            accept="image/*"
                            @change="onFilePicked"
                        >
                    </v-flex>
                    <v-flex xs12 sm6>
                        <v-img :title="this.editedItem.imageName" height="100" width="70" :alt="this.editedItem.imageName" :src="this.editedItem.imageUrl"></v-img>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field v-model="editedItem.dropPrice" class="inputPrice" label="Цена дроп" type="number" :rules="[v => !!v || 'Обязательное поле']"></v-text-field required>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field v-model="editedItem.retailPrice" class="inputPrice" label="Цена розница" type="number" :rules="[v => !!v || 'Обязательное поле']" required></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <!-- <v-text-field v-model="editedItem.sizes" label="Размеры"></v-text-field> -->
                        <v-select
                            v-model="editedItem.sizes"
                            :items="allSizes"
                            :menu-props="{ maxHeight: '400' }"
                            label="Размеры"
                            multiple
                            hint="Выберите размеры"
                            persistent-hint
                        ></v-select>
                    </v-flex>
                </v-layout>
            </v-container>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="error" flat @click="close">Отмена</v-btn>
            <v-btn :disabled="!valid" color="success" flat @click="save">Сохранить</v-btn>
          </v-card-actions>
        </v-form>
        </v-card>
      </v-dialog>
    </v-toolbar>
    <v-data-table
      :headers="headers"
      :items="products"
      class="elevation-1"
    >
      <template v-slot:items="props">
        <td>{{ props.item.name }}</td>
        <td class="text-xs-center"><v-img height="186" width="126" :src="props.item.imageUrl"></v-img></td>
        <td class="text-xs-center">{{ props.item.dropPrice }}</td>
        <td class="text-xs-center">{{ props.item.retailPrice }}</td>
        <td class="text-xs-center">
            <select size="8" multiple disabled>
                <option  v-for="(size, key) in allSizes" :value="size" :key="key + props.item.name"
                v-bind:style="[props.item.sizes.includes(size) ? {'background' :'green' } : {'background' :'red' }]"
                >
                {{ size }}
                </option>
            </select>
        </td>
        <td class="justify-center layout px-0">
          <v-icon
            small
            class="mr-2"
            @click="editItem(props.item)"
          >
            edit
          </v-icon>
          <v-icon
            small
            @click="deleteItem(props.item)"
          >
            delete
          </v-icon>
        </td>
      </template>
      <!-- <template v-slot:no-data>
        <v-btn color="grey darken-3" @click="initialize">Сброс</v-btn>
      </template> -->
    </v-data-table>
  </div>
</template>

<script>
  class Errors {
    constructor(){
        this.errors = {}
    }

    get(field){
        if(this.errors[field]){
        return this.errors[field][0];
        }
    }

    record(errors){
        this.errors = errors;
    }
  }
  export default {
    data: () => ({
        loader: null,
        loading3: false,
        valid: false,
        dialog: false,
        selected: '',
        multiple: "true",
        errors: new Errors,
        allSizes: [29, 30 , 31, 32, 33, 34, 35, 36, 37],
        headers: [
            { text: 'Наименование', align: 'left', sortable: false, value: 'name'},
            { text: 'Изображение', align: 'center', value: 'imageUrl', sortable: false },
            { text: 'Цена дроп', align: 'center', value: 'dropPrice' },
            { text: 'Цена розница', align: 'center', value: 'retailPrice' },
            { text: 'Размеры', align: 'center', value: 'sizes', sortable: false },
            { text: 'Действие', value: 'action', sortable: false }
        ],
        products: [],
        editedIndex: -1,
        editedItem: {
            name: '',
            imageName: '',
            imageUrl: '',
            imageFile: '',
            dropPrice: null,
            retailPrice: null,
            sizes: []
        },
        defaultItem: {
            name: '',
            imageName: '',
            imageUrl: '',
            imageFile: '',
            dropPrice: null,
            retailPrice: null,
            sizes: []
        }
    }),
    computed: {
        formTitle () {
            return this.editedIndex === -1 ? 'Новый продукт' : 'Редактировать продукт'
        },
    },
    watch: {
        dialog (val) {
            val || this.close()
        },
        loader () {
            const l = this.loader
            this[l] = !this[l]

            setTimeout(() => (this[l] = false), 3000)

            this.loader = null
        }
        // при изменениях маршрута запрашиваем данные снова
        // '$route': 'fetchData'
    },
    created () { //inside hook 'created' recommended do fetching data
        this.fetchData();
    },
    methods: {
        fetchData(){
            this.axios.get('products').then((response) => {
                this.products = response.data;
            })
        },
        createProduct(newProduct){
            console.log(11111);
            console.log(newProduct);
            this.axios({
                method: 'post',
                url: 'products',
                data: newProduct
            }).then((response) => {
                console.log(2222);
                console.log(response.status);
            }).catch(error => this.errors.record(error.response.data));
            // debugger;
        },
        pickFile (event) {
            // console.log(event)
            this.$refs.image.click ()
        },
        
        onFilePicked (e) {
            const files = e.target.files
            if(files[0] !== undefined) {
                this.loader = 'loading3'
                this.editedItem.imageName = files[0].name
                if(this.editedItem.imageName.lastIndexOf('.') <= 0) {
                    return
                }
                const fr = new FileReader ()
                fr.readAsDataURL(files[0])
                fr.addEventListener('load', () => {
                    this.editedItem.imageUrl = fr.result
                    this.editedItem.imageFile = files[0] // this is an image file that can be sent to server...
                })
            } else {
                this.editedItem.imageName = ''
                this.editedItem.imageFile = ''
                this.editedItem.imageUrl = ''
            }
        },
        editItem (item) {
            this.editedIndex = this.products.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialog = true
        },
        deleteItem (item) {
            const index = this.products.indexOf(item)
            confirm('Вы уверены что хотите удалить этот продукт?') && this.products.splice(index, 1)
        },
        close () {
            console.log(3333)
            this.dialog = false
            setTimeout(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
            this.editedIndex = -1
            }, 300)
        },
        save () {
            // console.log(5555)

            // debugger
            if (this.$refs.form.validate()) {
                if (this.editedIndex > -1) {
                    Object.assign(this.products[this.editedIndex], this.editedItem)
                } else {
                    this.createProduct(this.editedItem);
                    console.log(this.products.length)
                    this.products.push(this.editedItem)
                    console.log(this.products.length)
                }
                this.close()
            }
        }
    }
  }
</script>

<style scope>

select {
    padding: 3px;
    width: 100%;
    border-radius: 3px;
    text-align: center;
}

select option{
    color: white;
}

option{
    border: 1px solid black;
    margin: 1px;
}

/* .custom-loader {
    animation: loader 1s infinite;
    display: flex;
} */

@-moz-keyframes loader {
    from {
        transform: rotate(0);
    }
    to {
        transform: rotate(360deg);
    }
}
@-webkit-keyframes loader {
    from {
        transform: rotate(0);
    }
    to {
        transform: rotate(360deg);
    }
}
@-o-keyframes loader {
    from {
        transform: rotate(0);
    }
    to {
        transform: rotate(360deg);
    }
}
@keyframes loader {
    from {
        transform: rotate(0);
    }
    to {
        transform: rotate(360deg);
    }
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    /* display: none; <- Crashes Chrome on hover */
    -webkit-appearance: none;
    margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
}

.inputPrice input[type='number'] {
    -moz-appearance: textfield;
}

</style>