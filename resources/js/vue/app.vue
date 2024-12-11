<template>
    <div class="container mt-5">
        <div class="heading text-center mb-4">
            <h2 id="title" class="text-primary">Shopping List</h2>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Enter list name"
                        v-model="newListName"
                    />
                    <input
                        type="number"
                        class="form-control"
                        placeholder="Enter budget"
                        v-model.number="newBudget"
                    />
                    <div class="input-group-append">
                        <button class="btn btn-success" @click="createShoppingList">
                            Create List
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <shopping-list-form
            v-if="isFormVisible"
            :budget="listBudget"
            :list-name="listName"
            :list-id="listId"
            :items="items"
            @close="hideForm"
        ></shopping-list-form>

        <div v-if="lists.length" class="mt-5">

            <h3>Lists</h3>
            <ul class="list-group">
                <li v-for="list in lists" :key="list.id" class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>
                            <strong>{{ list.name }}</strong> - £{{ list.budget }}
                        </span>
                        <button
                            class="btn btn-primary btn-sm"
                            @click="viewList(list.id)"
                        >
                            View
                        </button>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import ShoppingListForm from "./ShoppingListForm.vue";
import axios from "axios";

export default {
    name: "App",
    components: {
        ShoppingListForm,
    },
    data() {
        return {
            newListName: '',
            newBudget: null,
            isFormVisible: false,
            listName: '',
            listBudget: null,
            listId: 0,
            lists: [],
            items: [],
        };
    },
    methods: {
        async createShoppingList() {
            if (!this.newListName.trim()) {
                alert('Please enter a valid list title.');
                return;
            }

            try {
                const response = await axios.post('/api/list', {
                    name: this.newListName,
                    budget: this.newBudget ?? 0,
                });

                const { id, name, budget } = response.data;
                this.listId = id;
                this.listName = name ?? '';
                this.listBudget = parseFloat(budget) ?? 0.0;

                this.isFormVisible = true;
                this.newListName = '';
                this.newBudget = null;

                await this.fetchLists();
            } catch (error) {
                alert('Failed to create shopping list. Please try again.');
            }
        },
        async fetchLists() {
            try {
                const response = await axios.get('/api/list/all');
                this.lists = response.data;
            } catch (error) {
                alert('Failed to fetch lists. Please try again.');
            }
        },
        async viewList(listId) {
            try {
                const response = await axios.get(`/api/list/${listId}`);
                const { id, name, budget, items } = response.data;

                this.listId = id;
                this.listName = name;
                this.listBudget = parseFloat(budget) ?? 0.0;
                this.items = items;

                this.isFormVisible = true;
            } catch (error) {
                alert('Failed to load the selected list.');
            }
        },
        hideForm() {
            this.isFormVisible = false;
        },
    },
    async mounted() {
        await this.fetchLists();
    },
};
</script>
