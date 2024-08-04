<script>
import axios from 'axios';
import tt from "@tomtom-international/web-sdk-maps";
import { services } from '@tomtom-international/web-sdk-services';
import SearchBox from '@tomtom-international/web-sdk-plugin-searchbox';
import { store } from '../../store';

export default {
    name: 'AddStopView',
    data() {
        return {
            store,
            newStopName: '',
            newStopNotes: '',
            newStopRating: '',
            newStopPositionLongitude: '',
            newStopPositionLatitude: '',
            loading: false,
            errors: ''
        }
    },
    methods: {
        createNewStop() {
            const data = {
                day_id: this.$route.params.id,
                name: this.newStopName,
                position_longitude: this.newStopPositionLongitude.toString(),
                position_latitude: this.newStopPositionLatitude.toString(),
                image: '',
                notes: this.newStopNotes,
                rating: this.newStopRating,
            }
            // console.log(data);

            this.loading = true

            axios.post(store.baseApiUrl + 'new-stop', data).then(response => {
                if (response.data.success) {
                    this.newStopName = ''
                    this.newStopPositionLongitude = ''
                    this.newStopPositionLatitude = ''
                    this.newStopNotes = ''
                    this.newStopRating = ''
                    this.errors = {}
                    // console.log(response.data.message);
                    this.$router.push({ name: 'day', params: { id: this.$route.params.id, date: this.$route.params.date } })
                }
            }).catch(error => {
                console.log(error);
                if (error) {
                    this.errors = error.response.data.errors
                }
            })

            this.loading = false
        },
        // map
        addMap() {
            const map = tt.map({
                key: store.mapKey,
                container: "map",
            })

            var options = {
                searchOptions: {
                    key: store.mapKey,
                    language: "en-GB",
                    limit: 5,
                },
                autocompleteOptions: {
                    key: store.mapKey,
                    language: "en-GB",
                    resultSet: "poi"
                },
            }
            var ttSearchBox = new SearchBox(services, options)
            map.addControl(ttSearchBox, "top-left")
            ttSearchBox.updateOptions({
                minNumberOfCharacters: 3,
                labels: {
                    placeholder: "Where are you going*?",
                },
            })

            ttSearchBox.on("tomtom.searchbox.resultselected", this.handleResultSelection)
            ttSearchBox.on("tomtom.searchbox.resultfocused", this.handleResultSelection)

            return map
        },
        handleResultSelection(e) {
            // console.log("selected", e);

            let map = this.addMap()
            let result = e.data.result

            if (result.type === 'POI') {
                this.newStopName = result.poi.name
            } else {
                this.newStopName = result.address.freeformAddress
            }

            let position = [result.position.lng, result.position.lat]
            let marker = new tt.Marker().setLngLat(position).addTo(map)
            let popup = new tt.Popup().setText(this.newStopName)
            marker.setPopup(popup)
            map.setZoom(8)
            map.setCenter(position)

            this.newStopPositionLongitude = result.position.lng
            this.newStopPositionLatitude = result.position.lat

        },
    },
    mounted() {
        this.addMap()
    }
}
</script>

<template>
    <section>
        <div class="row">
            <div class="col m-2">
                <!-- left col -->
                <form @submit.prevent="createNewStop()">
                    <div class="mm-3">
                        <label for="new-stop-title" class="form-label">Name / Location :</label>
                        <input type="text" class="form-control" name="new-stop-title" id="new-stop-title"
                            placeholder="Rome" v-model="newStopName" />
                    </div>

                    <div class="mb-3">
                        <label for="new-stop-description" class="form-label">Notes :</label>
                        <textarea class="form-control" name="new-stop-description" id="new-stop-description" rows="3"
                            v-model="newStopNotes"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="new-stop-rating" class="form-label">Stop rating :</label>
                        <input type="range" class="form-range" min="0" max="5" id="new-stop-rating"
                            v-model="newStopRating">
                    </div>

                    <div class="my-3">
                        <button class="form-control" type="submit" :disabled="loading">
                            {{ loading ? 'Creating...' : 'Add a new stop' }}
                        </button>
                    </div>
                </form>

                <div class="errors text-danger m-3" v-if="Object.keys(this.errors).length !== 0">
                    <div v-for="error in errors">{{ error[0] }}</div>
                </div>
            </div>
            <div class="col">
                <!-- right col -->
                <div id='map'></div>
            </div>
        </div>
    </section>
</template>

<style scoped></style>