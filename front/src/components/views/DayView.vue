<script>
import axios from 'axios';
import Map from '../partials/Map.vue';

export default {
    name: 'DayView',
    components: {
        Map
    },
    data() {
        return {
            day: {},
            stops: []
        }
    },
    methods: {
        getSingleDay() {
            axios.get('http://127.0.0.1:8000/api/day' + this.$route.params.id).then(response => {
                this.day = response.data.day[0]
                this.stops = response.data.stops
                // console.log(this.day, this.stops);
            })
        }
    },
    mounted() {
        this.getSingleDay()
    }
}
</script>

<template>
    <section>
        <div class="row">
            <div class="col">
                <div>
                    <h2 v-if="day.title">
                        {{ day.title }}
                    </h2>
                    <h2 v-else>
                        {{ day.date }}
                    </h2>
                    <p>
                        {{ day.description }}
                    </p>
                </div>
                <template v-for="stop in stops">
                    <h5>{{ stop.name }} - <span>{{ stop.rating }}/5</span></h5>
                    <p>{{ stop.notes }}</p>
                </template>
            </div>
            <div class="col">
                <Map :stops="stops" />
            </div>
        </div>
    </section>
</template>

<style scoped></style>