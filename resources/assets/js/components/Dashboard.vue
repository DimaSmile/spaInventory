<template>
  <div>
    <v-toolbar flat color="white">
      <v-toolbar-title>Current category</v-toolbar-title>
      <!-- <v-divider
        class="mx-2"
        inset
        vertical
      ></v-divider> -->
      <v-spacer></v-spacer>
      <v-dialog v-model="dialog" max-width="500px">
        <template v-slot:activator="{ on }">
          <v-btn color="primary" dark class="mb-2" v-on="on">New Item</v-btn>
        </template>
        <v-card>
          <v-card-title>
            <span class="headline">{{ formTitle }}</span>
          </v-card-title>

          <v-card-text>
            <v-container grid-list-md>
                <v-layout wrap>
                    <v-flex xs12 sm6 md4>
                        <v-text-field v-model="editedItem.name" label="Наименование"></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field v-model="editedItem.imageName" label="Выберети картинку" @click='pickFile' prepend-icon='attach_file'></v-text-field>
                        <input 
                            type="file"
                            style="display: none"
                            ref="image"
                            accept="image/*"
                            @change="onFilePicked"
                        >
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field v-model="editedItem.dropPrice" label="Цена дроп"></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field v-model="editedItem.retailPrice" label="Цена розница"></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <!-- <v-text-field v-model="editedItem.sizes" label="Размеры"></v-text-field> -->
                        <v-select
                            v-model="editedItem.sizes"
                            :items="allSizes"
                            :menu-props="{ maxHeight: '400' }"
                            label="Размеры"
                            multiple
                            hint="Pick your favorite states"
                            persistent-hint
                        ></v-select>
                    </v-flex>
                </v-layout>
            </v-container>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" flat @click="close">Отмена</v-btn>
            <v-btn color="blue darken-1" flat @click="save">Сохранить</v-btn>
          </v-card-actions>
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
      <template v-slot:no-data>
        <v-btn color="primary" @click="initialize">Reset</v-btn>
      </template>
    </v-data-table>
  </div>
</template>

<script>
  export default {
    data: () => ({
        dialog: false,
        selected: '',
        multiple: "true",
        allSizes: [29, 30 , 31, 32, 33, 34, 35, 36, 37],
        headers: [
            { text: 'Name', align: 'left', sortable: false, value: 'name'},
            { text: 'Image', align: 'center', value: 'imageUrl', sortable: false },
            { text: 'Цена дроп', align: 'center', value: 'dropPrice' },
            { text: 'Цена розница', align: 'center', value: 'retailPrice' },
            { text: 'Размеры', align: 'center', value: 'sizes' },
            { text: 'Actions', value: 'action', sortable: false }
        ],
        products: [],
        editedIndex: -1,
        editedItem: {
            name: '',
            imageName: '',
            imageUrl: '',
            imageFile: '',
            dropPrice: 0,
            retailPrice: 0,
            sizes: []
        },
        defaultItem: {
            name: '',
            imageName: '',
            imageUrl: '',
            imageFile: '',
            dropPrice: 0,
            retailPrice: 0,
            sizes: []
        }
    }),
    computed: {
        formTitle () {
            return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
        },
        // sortSizes (itemSizes) {
            
        // }
    },
    watch: {
        dialog (val) {
            val || this.close()
        }
    },
    created () {
        this.initialize()
    },
    methods: {
        pickFile () {
            this.$refs.image.click ()
        },
        
        onFilePicked (e) {
            const files = e.target.files
            if(files[0] !== undefined) {
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
        initialize () {
            this.products = [
            {
                name: 'Frozen Yogurt',
                imageUrl: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4hX7ktSvWlatywBvUSLsUjMKGDvI1aAY9haPf4QOeOBFT1GRn',
                dropPrice: 6.0,
                retailPrice: 24,
                sizes: [29, 35, 36, 37]
            },
            {
                name: 'Frozen Yogurt',
                imageUrl: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4hX7ktSvWlatywBvUSLsUjMKGDvI1aAY9haPf4QOeOBFT1GRn',
                dropPrice: 6.0,
                retailPrice: 24,
                sizes: [29, 30 , 31, 32, 33, 34, 35, 36, 37]
            },
            {
                name: 'Frozen Yogurt',
                imageUrl: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4hX7ktSvWlatywBvUSLsUjMKGDvI1aAY9haPf4QOeOBFT1GRn',
                dropPrice: 6.0,
                retailPrice: 24,
                sizes: [29, 30 , 31, 32, 33, 34, 35, 36, 37]
            },
            {
                name: 'Frozen Yogurt',
                imageUrl: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4hX7ktSvWlatywBvUSLsUjMKGDvI1aAY9haPf4QOeOBFT1GRn',
                dropPrice: 6.0,
                retailPrice: 24,
                sizes: [29, 30 , 31, 32, 33, 34, 35, 36, 37]
            },
            {
                name: 'Frozen Yogurt',
                imageUrl: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4hX7ktSvWlatywBvUSLsUjMKGDvI1aAY9haPf4QOeOBFT1GRn',
                dropPrice: 6.0,
                retailPrice: 24,
                sizes: [29, 30 , 31, 32, 33, 34, 35, 36, 37]
            },
            {
                name: 'Frozen Yogurt',
                imageUrl: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4hX7ktSvWlatywBvUSLsUjMKGDvI1aAY9haPf4QOeOBFT1GRn',
                dropPrice: 6.0,
                retailPrice: 222,
                sizes: [29, 30 , 31, 32, 33, 34, 35]
            },
            {
                name: 'Frozen Yogurt',
                imageUrl: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4hX7ktSvWlatywBvUSLsUjMKGDvI1aAY9haPf4QOeOBFT1GRn',
                dropPrice: 6.0,
                retailPrice: 24,
                sizes: [29, 37]
            },
            {
                name: 'Frozen Yogurt',
                imageUrl: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4hX7ktSvWlatywBvUSLsUjMKGDvI1aAY9haPf4QOeOBFT1GRn',
                dropPrice: 6.0,
                retailPrice: 24,
                sizes: [29, 37]
            },
            {
                name: 'Frozen Yogurt',
                imageUrl: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4hX7ktSvWlatywBvUSLsUjMKGDvI1aAY9haPf4QOeOBFT1GRn',
                dropPrice: 6.0,
                retailPrice: 24,
                sizes: [ 34, 35, 36, 37]
            },
            {
                name: 'Frozen Yogurt',
                imageUrl: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4hX7ktSvWlatywBvUSLsUjMKGDvI1aAY9haPf4QOeOBFT1GRn',
                dropPrice: 6.0,
                retailPrice: 24,
                sizes: [29]
            },
            {
                name: 'Frozen Yogurt',
                imageUrl: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4hX7ktSvWlatywBvUSLsUjMKGDvI1aAY9haPf4QOeOBFT1GRn',
                dropPrice: 6.0,
                retailPrice: 24,
                sizes: [ 31, 32, 33, 34, 35, 36, 37]
            },
            {
                name: 'Frozen Yogurt',
                imageUrl: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4hX7ktSvWlatywBvUSLsUjMKGDvI1aAY9haPf4QOeOBFT1GRn',
                dropPrice: 6.0,
                retailPrice: 24,
                sizes: [29, 30 , 35, 36, 37]
            },
            {
                name: 'Frozen Yogurt',
                imageUrl: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4hX7ktSvWlatywBvUSLsUjMKGDvI1aAY9haPf4QOeOBFT1GRn',
                dropPrice: 6.0,
                retailPrice: 24,
                sizes: [29, 30 , 31, 32]
            }
            ]
        },
        editItem (item) {
            this.editedIndex = this.products.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialog = true
        },
        deleteItem (item) {
            const index = this.products.indexOf(item)
            confirm('Are you sure you want to delete this item?') && this.products.splice(index, 1)
        },
        close () {
            this.dialog = false
            setTimeout(() => {
            this.editedItem = Object.assign({}, this.defaultItem)
            this.editedIndex = -1
            }, 300)
        },
        save () {
            if (this.editedIndex > -1) {
                Object.assign(this.products[this.editedIndex], this.editedItem)
            } else {
                this.products.push(this.editedItem)
            }
            this.close()
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

</style>