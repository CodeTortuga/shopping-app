<template>
    <tr :class="{ 'table-success': item.picked_up }">
        <td>
            <input
                type="checkbox"
                :checked="item.picked_up"
                @change="togglePickedUp"
            />
        </td>
        <td>
            <template v-if="isEditing">
                <input
                    type="text"
                    class="form-control"
                    v-model="editableItem.name"
                />
            </template>
            <template v-else>
                <span :class="{ 'text-decoration-line-through text-muted': item.picked_up }">
                    {{ item.name }}
                </span>
            </template>
        </td>
        <td>
            <template v-if="isEditing">
                <input
                    type="number"
                    class="form-control"
                    v-model.number="editableItem.price"
                />
            </template>
            <template v-else>
                £{{ item.price }}
            </template>
        </td>
        <td>
            <template v-if="isEditing">
                <input
                    type="number"
                    class="form-control"
                    v-model.number="editableItem.quantity"
                />
            </template>
            <template v-else>
                {{ item.quantity }}
            </template>
        </td>
        <td>
            <button
                class="btn btn-sm btn-success"
                v-if="isEditing"
                @click="saveEdit"
            >
                Save
            </button>
            <button
                class="btn btn-sm btn-warning"
                v-else
                @click="toggleEdit"
            >
                ✏️
            </button>
        </td>
        <td>
            <button
                class="btn btn-sm btn-danger"
                @click="removeItem"
            >🗑️
            </button>
        </td>
    </tr>
</template>

<script>
import axios from "axios";

export default {
    name: "ShoppingListItem",
    props: {
        item: {
            type: Object,
            required: true,
        },
        listId: {
            type: Number,
            required: true,
        },
    },
    data() {
        return {
            isEditing: false,
            editableItem: { ...this.item },
        };
    },
    methods: {
        async togglePickedUp() {
            try {
                await axios.patch(`/api/items/${this.item.id}/toggle-picked-up`);
                this.$emit('update-item', { ...this.item, picked_up: !this.item.picked_up });
            } catch (error) {
                alert('Failed to update item. Please try again.');
            }
        },
        toggleEdit() {
            this.isEditing = !this.isEditing;
        },
        async saveEdit() {
            try {
                const updatedItem = await axios.put(`/api/items/${this.item.id}`, {
                    name: this.editableItem.name,
                    price: this.editableItem.price,
                    quantity: this.editableItem.quantity,
                    shopping_list_id: this.listId,
                });
                this.$emit('update-item', updatedItem.data);
                this.isEditing = false;
            } catch (error) {
                alert('Failed to update item. Please try again.');
            }
        },
        async removeItem() {
            try {
                await axios.delete(`/api/items/${this.item.id}`);
                this.$emit('remove-item', this.item.id);
            } catch (error) {
                alert('Failed to remove item. Please try again.');
            }
        },
    },
};
</script>
