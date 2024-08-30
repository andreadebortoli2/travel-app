<script>
import axios from 'axios';
import { store } from '../../store';

export default {
    name: 'NewDay',
    props: {
        trip: Object
    },
    data() {
        return {
            store,
            newDayTitle: '',
            newDayDate: '',
            newDayDescription: '',
            errors: {},
            loading: false,
        }
    },
    methods: {
        createNewDay() {
            const data = {
                trip_id: this.trip.id,
                title: this.newDayTitle,
                date: this.newDayDate,
                description: this.newDayDescription
            }
            // console.log(data);

            this.loading = true

            axios.post(store.baseApiUrl + 'new-day', data).then(response => {
                if (response.data.success) {
                    this.newDayTitle = ''
                    this.newDayDate = ''
                    this.newDayDescription = ''
                    this.errors = {}
                    // console.log(response.data.message);

                    // console.log(document.getElementById(`new-day-${this.trip.id}`).hidden)
                    document.getElementById(`new-day-${this.trip.id}`).hidden = true
                    let modalBackground = document.querySelector('.offcanvas-backdrop')
                    if (modalBackground) {
                        modalBackground.remove()
                    }

                    let dayId = response.data.route.day_id
                    let dayDate = response.data.route.day_date
                    this.$router.push({ name: 'day', params: { id: dayId, date: dayDate } })
                }
            }).catch(error => {
                console.log(error);
                if (error) {
                    this.errors = error.response.data.errors
                }
            })

            this.loading = false
        },
    }
}
</script>

<template>
    <button class="btn btn-outline-light text-primary-emphasis" type="button" data-bs-toggle="offcanvas"
        :data-bs-target="`#new-day-${trip.id}`" :aria-controls="`new-day-${trip.id}`">
        <h2 class="m-0">
            <div v-html="store.addIcon"></div>
        </h2>
    </button>

    <div class="offcanvas offcanvas-start bg-info-subtle" data-bs-scroll="true" tabindex="-1" :id="`new-day-${trip.id}`"
        :aria-labelledby="`new-day-${trip.id}-offcanvas`">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" :id="`new-day-${trip.id}-offcanvas`">
                New day
            </h5>
            <button type="button" class="btn-close" :id="`close-new-day-${trip.id}-offcanvas`"
                data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body">
            <form @submit.prevent="createNewDay()">

                <div class="mm-3">
                    <label for="new-day-title" class="form-label">Title / destination :</label>
                    <input type="text" class="form-control" name="new-day-title" id="new-day-title" placeholder="Rome"
                        v-model="newDayTitle" />
                </div>

                <div class="my-3">
                    <label for="new-day-date" class="form-label">Date* :</label>
                    <input type="date" class="form-control" name="new-day-date" id="new-day-date" v-model="newDayDate"
                        required>
                </div>

                <div class="mb-3">
                    <label for="new-day-description" class="form-label">Description :</label>
                    <textarea class="form-control" name="new-day-description" id="new-day-description" rows="3"
                        v-model="newDayDescription"></textarea>
                </div>


                <div class="my-3">
                    <button class="form-control btn btn-light" type="submit" :disabled="loading">
                        {{ loading ? 'Creating...' : 'Add a new day' }}
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