<template>
  <div>
    <v-toolbar flat color="white">
      <v-toolbar-title>{{$route.params.category_id}} {{ $route.params.category_name }}</v-toolbar-title>
      <!-- <v-divider
        class="mx-2"
        inset
        vertical
      ></v-divider> -->
      <v-spacer></v-spacer>
      <v-dialog v-model="dialog" max-width="500px">
            <template v-slot:activator="{ on }">
                <v-btn v-on="on"
                       color="grey darken-3"
                       dark
                       class="mb-2">
                    Новый продукт
                </v-btn>
            </template>
        <v-card>
          <v-card-title>
            <span class="headline">{{ formTitle }}</span>
          </v-card-title>

        <v-form @submit.prevent="save"
                ref="form"
                id="formId"
                method="post"
                v-model="valid"
                lazy-validation>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout wrap>
                        <v-flex xs12 sm6>
                            <v-text-field v-model="editedItem.name"
                                          name="name"
                                          label="Наименование"
                                          :rules="[v => !!v || 'Имя обязательно']"
                                          required>
                            </v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6>
                            <v-text-field v-model="editedItem.sku"
                                          name="sku"
                                          label="Артикул"
                                          :rules="[isSku]">
                            </v-text-field> <!-- required -->
                        </v-flex>
                        <v-flex xs12 sm6>
                            <!-- <v-text-field v-model="editedItem.imageName" hint="Выберите изображение" persistent-hint label="" @click='pickFile' prepend-icon='attach_file'></v-text-field> -->
                            <v-btn @click="pickFile" 
                                   :loading="loading3"
                                   :disabled="loading3"
                                   color="grey darken-3"
                                   class="white--text"
                                   depressed>
                                Загрузить
                                <v-icon right dark>cloud_upload</v-icon>
                            </v-btn>
                            <p class="text-md-left">
                                {{ editedItem.imageName ? editedItem.imageName : 'Выберите изображение'}}
                            </p>
                            <input @change="onFilePicked" 
                                   type="file"
                                   style="display: none"
                                   ref="image"
                                   name="image"
                                   accept="image/*"
                                   id="image-upload">
                        </v-flex>
                        <v-flex xs12 sm6>
                            <v-img :title="editedItem.imageName"
                                   :src="editedItem.imageUrl"
                                   height="100"
                                   width="70">
                            </v-img>
                        </v-flex>
                        <v-flex xs12 sm6 md4>
                            <v-text-field v-model.trim.number="editedItem.dropPrice"
                                          name="dropPrice"
                                          error-count="2"
                                          class="inputPrice"
                                          label="Цена дроп"
                                          type="number"
                                          :counter="6"
                                          :rules="[priceRules.requiredLenth]">
                            </v-text-field>
                        </v-flex>
                        <!-- priceRules.isNumber @blur="resetValidation" -->
                        <v-flex xs12 sm6 md4>
                            <v-text-field v-model.trim.number="editedItem.retailPrice"
                                          name="retailPrice"
                                          error-count="2"
                                          class="inputPrice"
                                          label="Цена розница"
                                          type="number"
                                          :counter="6"
                                          :rules="[priceRules.requiredLenth]">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 md4>
                            <!-- <v-text-field v-model="editedItem.sizes" label="Размеры"></v-text-field> -->
                            <v-select v-model="editedItem.sizes"
                                      :items="allSizes"
                                      :menu-props="{ maxHeight: '400' }"
                                      label="Размеры"
                                      multiple
                                      hint="Выберите размеры"
                                      persistent-hint>
                            </v-select>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card-text>

        </v-form>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn @click="close"
                   color="error"
                   flat>
                Отмена
            </v-btn>
            <v-btn :disabled="!valid"
                   type="submit"
                   form="formId"
                   color="success"
                   flat>
                Сохранить
            </v-btn>
        </v-card-actions>
        </v-card>
      </v-dialog>
    </v-toolbar>
    <v-data-table :headers="headers"
                  :items="products"
                  class="elevation-1">
        <template v-slot:items="props">
            <!-- <td>{{ props.item}}</td> -->
            <td>{{ props.item.name }} / {{ props.item.sku }}</td>
            <td class="text-xs-center">
                <v-img class="centerimage"
                    height="186"
                    width="126"
                    :src="props.item.imageUrl">
                </v-img>
            </td>
            <td class="text-xs-center">{{ props.item.dropPrice }}</td>
            <td class="text-xs-center">{{ props.item.retailPrice }}</td>
            <td class="text-xs-center">
                <select size="8" multiple disabled>
                    <option v-for="(size, key) in allSizes"
                            :value="size"
                            :key="key + props.item.name"
                            :style="[props.item.sizes.includes(size) ? {'background' :'green' } : {'background' :'red' }]">
                        {{ size }}
                    </option>
                </select>
            </td>
            <td class="justify-center layout px-0">
                <v-icon @click="editProductForm(props.item)"
                        small
                        class="mr-2">
                    edit
                </v-icon>
                <v-icon @click="deleteProduct(props.item)"
                        small>
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
    // props: ['category_id' ,'category_name'],
    // props: {
    //     category_id: Number,
    //     category_name: String
    // },
    data: () => ({
        loader: null,
        loading3: false,
        valid: false,
        dialog: false,
        selected: '',
        multiple: "true",
        errors: new Errors,
        categoryId: '',
        categoryName: '',
        priceRules: {
            requiredLenth: v => !!v ? v.toString().length <= 6 || 'Максимум 6 символов': true,
            // isNumber: v => {
            //     const pattern = /^(\d*([.,](?=\d{3}))?\d+)+((?!\2)[.,]\d\d)?$/
            //     return v ? pattern.test(v) || 'Введите число' : true;
            // }
        },
        // isRequired: [ v => !!v || 'Артикул обязателен' ],
        allSizes: [29, 30 , 31, 32, 33, 34, 35, 36, 37],
        headers: [
            { text: 'Наименование / Артикул', align: 'left', sortable: false, value: 'name'},
            { text: 'Изображение', align: 'center', value: 'imageUrl', sortable: false },
            { text: 'Цена дроп', align: 'center', value: 'dropPrice' },
            { text: 'Цена розница', align: 'center', value: 'retailPrice' },
            { text: 'Размеры', align: 'center', value: 'sizes', sortable: false },
            { text: 'Действие', value: 'action', sortable: false }
        ],
        products: [],
        editedIndex: -1,
        editedItem: {
            id: '',
            name: '',
            sku: '',
            imageUrl: '',
            imageName: '',
            dropPrice: '',
            retailPrice: '',
            sizes: []
        },
        defaultItem: {
            id: '',
            name: '',
            sku: '',
            imageUrl: '',
            imageName: '',
            dropPrice: '',
            retailPrice: '',
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

            setTimeout(() => (this[l] = false), 500)

            this.loader = null
        },
        // при изменениях маршрута запрашиваем данные снова
        '$route': 'fetchData'
    },
    created () { //inside hook 'created' recommended do fetching data
            // from router vuejs documentation
            // fetch the data when the view is created and the data is
            // already being observed
        this.fetchData();
    },
    methods: {
        fetchData(){
            if(this.$route.params.category_id){
                this.axios.get('categories/'+ this.$route.params.category_id).then((response) => {
                    console.log(response.data);
                    // console.log(555555);debugger
                    this.products = response.data;
                })
            }else{
                this.axios.get('products').then((response) => {
                    this.products = response.data;
                    // console.log(this.products);debugger;
                })
            }
        },
        createProduct(newProduct){
            this.axios({
                method: 'post',
                url: 'products',
                headers: { 'content-type': 'multipart/form-data' },
                data: newProduct
            }).then((response) => {
                // console.log(response);
                this.products.push(this.editedItem);
            }).catch(error => this.errors.record(error.response.data));
        },
        updateProduct(formData){
            this.axios.post( 'products/' + this.editedItem.id, formData
            // {
            //     url: 'products/' + this.editedItem.id,
            //     method: 'patch',
            //     headers: { 'content-type': 'multipart/form-data' },
            //     data: formData 
            // }
            ).then((response) => {
                // console.log(response);
                Object.assign(this.products[this.editedIndex], this.editedItem)
            }).catch(error => this.errors.record(error.response.data));
        },
        deleteProduct(product){
            let prodId = product.id;
            const index = this.products.indexOf(product);
            let remove = confirm('Вы уверены что хотите удалить этот продукт?');

            if(remove){
                this.axios.delete('products/' + prodId)
                .then((response) => {
                    // console.log(response);
                    this.products.splice(index, 1)
                }).catch((error) => {
                    this.errors.record(error.response.data)
                    alert("Product not removed")
                });
            }
        },
        pickFile (event) {
            this.$refs.image.click ()
        },
        isSku(inputValue){
            if (this.editedIndex > -1) {
                if (this.products[this.editedIndex].sku === inputValue) {
                    return false;
                }
            }
            let rule = this.products.find(o => o.sku === inputValue); 
            if(rule){
                return 'Артикул существует';
            }
            //if you need required field
            // else if (inputValue == ''){
            //     return 'Артикул обязателен';
            // }
            return false;
        },
        resetValidation () {
            this.$refs.form.resetValidation()
        },
        onFilePicked (e) {
            const files = e.target.files
            if(files[0] !== undefined) {
                this.editedItem.imageName = files[0].name
                if(this.editedItem.imageName <= 0) {
                    return
                }

                // const fileReader = new FileReader ()
                // diff beetween FileReader.readAsDataURL and URL.createObjectURL
                this.loader = 'loading3'

                // fileReader.addEventListener('load', () => {
                //     this.editedItem.imageUrl = fileReader.result;//result содержит данные после чтения файла методом readAsDataURL
                // })
                // fileReader.readAsDataURL(files[0]);
                // this.editedItem.image = files[0];
                this.editedItem.imageUrl = URL.createObjectURL(files[0]);
            } else {
                this.editedItem.imageName = '';
                this.editedItem.imageUrl = '';
            }
        },
        editProductForm (item) {
            this.$refs.form.resetValidation()
            this.editedIndex = this.products.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialog = true
        },
        // deleteItem (item) {
        //     console.log(item);debugger;
        //     deleteProduct(item);
        //     const index = this.products.indexOf(item)
        //     confirm('Вы уверены что хотите удалить этот продукт?') && this.products.splice(index, 1)
        // },
        close () {
            this.$refs.form.resetValidation()
            this.dialog = false
            //URL.revokeObjectURL(this.editedItem.imageUrl);// освобождает URL-объект
            setTimeout(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
            this.editedIndex = -1
            }, 300)
        },
        save (event) {
            var formData = new FormData(event.target);
            this.editedItem.sku = this.isEmptyOrSpaces(this.editedItem.sku);
            // formData.append('sku', this.editedItem.sku);
            if (this.$refs.form.validate()) {
                if (this.editedIndex > -1) { //edit product
                    formData.append('_method', 'PATCH');
                    // this.editProduct(formData);
                    this.updateProduct(formData);
                } else { //new product
                    this.createProduct(formData);
                }
                this.close()
            }
        },
        isEmptyOrSpaces(str){
            if (str === null || str.match(/^ *$/) !== null){
                str = null;
                return str;
            }
            return str;
        }
    }
  }
</script>

<style>

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

.centerimage{
    margin: 0 auto;
}
</style>