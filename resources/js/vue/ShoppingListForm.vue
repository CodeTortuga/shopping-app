<template>
    <div class="container mt-4">
        <h2>{{ listName }}</h2>

        <div class="input-group mb-3">
            <input
                type="text"
                class="form-control"
                placeholder="Add item"
                v-model="newItem.name"
            />
            <input
                type="number"
                class="form-control"
                placeholder="Price"
                v-model.number="newItem.price"
            />
            <input
                type="number"
                class="form-control"
                placeholder="Quantity"
                v-model.number="newItem.quantity"
            />
            <div class="input-group-append">
                <button class="btn btn-primary" @click="addItem">
                    +
                </button>
            </div>
        </div>

        <div class="mt-3">
            <table class="table table-bordered table-striped">
                <thead class="thead-light">
                <tr>
                     <th class="text-center" style="width:10%;">Picked Up</th>
                    <th class="text-center" style="width:30%;">Name</th>
                    <th class="text-center" style="width:20%;">Price</th>
                    <th class="text-center" style="width:20%;">Quantity</th>
                    <th class="text-center" style="width:10%;">Edit</th>
                    <th class="text-center" style="width:10%;">Delete</th>
                </tr>
                </thead>
                <tbody>
                <shopping-list-item
                    v-for="(item, index) in localItems"
                    :key="index"
                    :item="item"
                    :list-id="listId"
                    @remove-item="removeItem(index)"
                    @toggle="togglePickedUp(index)"
                    @update-item="updateItem"
                ></shopping-list-item>
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <p><strong>Budget:</strong> £{{ (budget || 0).toFixed(2) }}</p>
            <p><strong>Total:</strong> £{{ (total || 0).toFixed(2) }}</p>
            <p :style="{ color: remaining < 0 ? 'red' : 'black' }">
                <strong>Remaining:</strong> £{{ (remaining || 0).toFixed(2) }}
            </p>
        </div>
    </div>
</template>

<script>
import ShoppingListItem from "./ShoppingListItem.vue";
import axios from "axios";

export default {
    name: "ShoppingListForm",
    components: {
        ShoppingListItem,
    },
    props: {
        listId: {
            type: Number,
        },
        budget: {
            type: Number,
            default: 0,
        },
        listName: {
            type: String,
        },
        items: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            newItem: {
                name: '',
                price: null,
                pickedUp: false,
                quantity: null,
            },
            localItems: [...this.items],
        };
    },
    computed: {
        total() {
            return this.localItems.reduce(
                (sum, item) => sum + (parseFloat(item.price) || 0) * (parseInt(item.quantity) || 1),
                0
            );
        },
        remaining() {
            return (parseFloat(this.budget) || 0) - this.total;
        },
    },
    methods: {
        async addItem() {
            if (!this.newItem.name || this.newItem.price <= 0 || this.newItem.quantity <= 0) {
                alert('Please provide a valid name, price, and quantity.');
                return;
            }

            try {
                await axios.post(`/api/items`, {
                    name: this.newItem.name,
                    price: this.newItem.price,
                    quantity: this.newItem.quantity,
                    picked_up: this.newItem.pickedUp,
                    shopping_list_id: this.listId,
                });

                await this.fetchItems();
                this.resetNewItem();
            } catch (error) {
                alert('Failed to add item. Please try again.');
            }
        },
        async fetchItems() {
            try {
                const response = await axios.get(`/api/list/${this.listId}`);
                this.localItems = response.data.items;
            } catch (error) {
                alert('Failed to fetch items. Please try again.');
            }
        },
        removeItem(index) {
            this.localItems.splice(index, 1);
        },
        togglePickedUp(index) {
            this.localItems[index].picked_up = !this.localItems[index].picked_up;
        },
        updateItem(updatedItem) {
            const index = this.localItems.findIndex((item) => item.id === updatedItem.id);
            if (index !== -1) {
                this.localItems.splice(index, 1, updatedItem);
            }
        },
        resetNewItem() {
            this.newItem = {
                name: '',
                price: null,
                pickedUp: false,
                quantity: null,
            };
        },
    },
    watch: {
        listId: {
            immediate: true,
            handler(newListId) {
                this.fetchItems();
            },
        },
        items: {
            immediate: true,
            deep: true,
            handler(newItems) {
                this.localItems = [...newItems];
            },
        },
    },
};
</script>
