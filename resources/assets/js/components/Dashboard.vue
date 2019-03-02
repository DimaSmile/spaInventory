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
                        <v-text-field 
                            v-model="editedItem.image" @click='pickFile' prepend-icon='attach_file' label="Картинка"></v-text-field>
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
                        <v-text-field v-model="editedItem.sizes" label="Размеры"></v-text-field>
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
      :items="desserts"
      class="elevation-1"
    >
      <template v-slot:items="props">
        <td>{{ props.item.name }}</td>
        <td class="text-xs-right"><v-img height="186" width="126" :src="props.item.image"></v-img></td>
        <td class="text-xs-right">{{ props.item.dropPrice }}</td>
        <td class="text-xs-right">{{ props.item.retailPrice }}</td>
        <td class="text-xs-right">{{ props.item.sizes }}</td>
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
        // imageName: '',
        imageUrl: '',
        imageFile: '',
        dialog: false,
        headers: [
            {
            text: 'Name',
            align: 'left',
            sortable: false,
            value: 'name'
            },
            { text: 'Image', value: 'image', sortable: false },
            { text: 'Цена дроп', value: 'dropPrice' },
            { text: 'Цена розница', value: 'retailPrice' },
            { text: 'Размеры', value: 'sizes' },
            { text: 'Actions', value: 'action', sortable: false }
        ],
        desserts: [],
        editedIndex: -1,
        editedItem: {
            name: '',
            image: 0,
            dropPrice: 0,
            retailPrice: 0,
            sizes: 0
        },
        defaultItem: {
            name: '',
            image: 0,
            dropPrice: 0,
            retailPrice: 0,
            sizes: 0
        }
    }),
    computed: {
        formTitle () {
            return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
        }
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
				this.editedItem.image = files[0].name
				if(this.editedItem.image.lastIndexOf('.') <= 0) {
					return
				}
				const fr = new FileReader ()
				fr.readAsDataURL(files[0])
				fr.addEventListener('load', () => {
					this.imageUrl = fr.result
					this.imageFile = files[0] // this is an image file that can be sent to server...
				})
			} else {
				this.editedItem.image = ''
				this.imageFile = ''
				this.imageUrl = ''
			}
		},
        initialize () {
            this.desserts = [
            {
                name: 'Frozen Yogurt',
                image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4hX7ktSvWlatywBvUSLsUjMKGDvI1aAY9haPf4QOeOBFT1GRn',
                dropPrice: 6.0,
                retailPrice: 24,
                sizes: 4.0
            },
            {
                name: 'Frozen Yogurt',
                image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4hX7ktSvWlatywBvUSLsUjMKGDvI1aAY9haPf4QOeOBFT1GRn',
                dropPrice: 6.0,
                retailPrice: 24,
                sizes: 4.0
            },
            {
                name: 'Frozen Yogurt',
                image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4hX7ktSvWlatywBvUSLsUjMKGDvI1aAY9haPf4QOeOBFT1GRn',
                dropPrice: 6.0,
                retailPrice: 24,
                sizes: 4.0
            },
            {
                name: 'Frozen Yogurt',
                image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4hX7ktSvWlatywBvUSLsUjMKGDvI1aAY9haPf4QOeOBFT1GRn',
                dropPrice: 6.0,
                retailPrice: 24,
                sizes: 4.0
            },
            {
                name: 'Frozen Yogurt',
                image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4hX7ktSvWlatywBvUSLsUjMKGDvI1aAY9haPf4QOeOBFT1GRn',
                dropPrice: 6.0,
                retailPrice: 24,
                sizes: 4.0
            },
            {
                name: 'Frozen Yogurt',
                image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4hX7ktSvWlatywBvUSLsUjMKGDvI1aAY9haPf4QOeOBFT1GRn',
                dropPrice: 6.0,
                retailPrice: 222,
                sizes: 4.0
            },
            {
                name: 'Frozen Yogurt',
                image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4hX7ktSvWlatywBvUSLsUjMKGDvI1aAY9haPf4QOeOBFT1GRn',
                dropPrice: 6.0,
                retailPrice: 24,
                sizes: 4.0
            },
            {
                name: 'Frozen Yogurt',
                image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4hX7ktSvWlatywBvUSLsUjMKGDvI1aAY9haPf4QOeOBFT1GRn',
                dropPrice: 6.0,
                retailPrice: 24,
                sizes: 4.0
            },
            {
                name: 'Frozen Yogurt',
                image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4hX7ktSvWlatywBvUSLsUjMKGDvI1aAY9haPf4QOeOBFT1GRn',
                dropPrice: 6.0,
                retailPrice: 24,
                sizes: 4.0
            },
            {
                name: 'Frozen Yogurt',
                image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4hX7ktSvWlatywBvUSLsUjMKGDvI1aAY9haPf4QOeOBFT1GRn',
                dropPrice: 6.0,
                retailPrice: 24,
                sizes: 4.0
            },
            {
                name: 'Frozen Yogurt',
                image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4hX7ktSvWlatywBvUSLsUjMKGDvI1aAY9haPf4QOeOBFT1GRn',
                dropPrice: 6.0,
                retailPrice: 24,
                sizes: 4.0
            },
            {
                name: 'Frozen Yogurt',
                image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4hX7ktSvWlatywBvUSLsUjMKGDvI1aAY9haPf4QOeOBFT1GRn',
                dropPrice: 6.0,
                retailPrice: 24,
                sizes: 4.0
            },
            {
                name: 'Frozen Yogurt',
                image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4hX7ktSvWlatywBvUSLsUjMKGDvI1aAY9haPf4QOeOBFT1GRn',
                dropPrice: 6.0,
                retailPrice: 24,
                sizes: 4.0
            }
            ]
        },
        editItem (item) {
            this.editedIndex = this.desserts.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialog = true
        },
        deleteItem (item) {
            const index = this.desserts.indexOf(item)
            confirm('Are you sure you want to delete this item?') && this.desserts.splice(index, 1)
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
            Object.assign(this.desserts[this.editedIndex], this.editedItem)
            } else {
            this.desserts.push(this.editedItem)
            }
            this.close()
        }
    }
  }
</script>