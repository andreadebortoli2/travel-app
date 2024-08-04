<script>
import axios from 'axios';
import { store } from '../../store';

export default {
    name: 'UpdateDay',
    props: {
        day: Object
    },
    data() {
        return {
            store,
            updateDayTitle: '',
            updateDayDate: '',
            updateDayDescription: '',
            errors: {},
            loading: false,
        }
    },
    methods: {
        setUpdateValues(day) {
            this.updateDayTitle = ''
            this.updateDayDate = ''
            this.updateDayDescription = ''
            this.updateDayTitle = day.title
            this.updateDayDate = day.date
            this.updateDayDescription = day.description
        },
        editDay(id) {
            const data = {
                trip_id: this.day.trip_id,
                title: this.updateDayTitle,
                date: this.updateDayDate,
                description: this.updateDayDescription
            }
            // console.log(data);

            this.loading = true

            axios.post(store.baseApiUrl + 'update-day/' + id, data).then(response => {
                if (response.data.success) {
                    this.updateDayTitle = ''
                    this.updateDayDate = ''
                    this.updateDayDescription = ''
                    this.errors = {}
                    // console.log(response.data.message);
                    this.$emit('updateDay')
                    document.getElementById(`close-update-day-offcanvas-${id}`).click()
                }
            }).catch(error => {
                console.log(error);
                if (error) {
                    this.errors = error.response.data.errors
                }
            })

            this.loading = false
        },
    },
    emits: ['updateDay']
}
</script>

<template>
    <button class="btn" type="button" data-bs-toggle="offcanvas" :data-bs-target="`#edit-day-${day.id}`"
        :aria-controls="`edit-day-${day.id}`" @click="setUpdateValues(day)">
        U
    </button>

    <div class="offcanvas offcanvas-end w-50" tabindex="-1" :id="`edit-day-${day.id}`"
        :aria-labelledby="`edit-day-${day.id}-offcanvas`">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" :id="`edit-day-${day.id}-offcanvas`">
                Edit day: {{ day.title }}
            </h5>
            <button type="button" class="btn-close" :id="`close-update-day-offcanvas-${day.id}`"
                data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body">
            <form @submit.prevent="editDay(day.id)">

                <div class="mm-3">
                    <label for="edit-day-title" class="form-label">Title / destination
                        :</label>
                    <input type="text" class="form-control" name="edit-day-title" id="edit-day-title"
                        v-model="updateDayTitle" />
                </div>

                <div class="my-3">
                    <label for="edit-day-date" class="form-label">Date*
                        :</label>
                    <input type="text" onfocus="(this.type='date')" class="form-control" name="edit-day-date"
                        id="edit-day-date" v-model="updateDayDate" required>
                </div>

                <div class="mb-3">
                    <label for="update-day-description" class="form-label">Description :</label>
                    <textarea class="form-control" name="update-day-description" id="update-day-description" rows="3"
                        v-model="updateDayDescription"></textarea>
                </div>

                <div class="my-3">
                    <button class="form-control" type="submit" :disabled="loading">
                        {{ loading ? 'Updating...' : 'Update the trip' }}
                    </button>
                </div>
            </form>

            <div class="errors text-danger m-3" v-if="Object.keys(this.errors).length !== 0">
                <div v-for="error in errors">{{ error[0] }}</div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>