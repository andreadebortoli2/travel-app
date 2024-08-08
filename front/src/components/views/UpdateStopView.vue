<script>
import axios from 'axios';
import tt from "@tomtom-international/web-sdk-maps";
import { services } from '@tomtom-international/web-sdk-services';
import SearchBox from '@tomtom-international/web-sdk-plugin-searchbox';
import { store } from '../../store';

export default {
    name: 'UpdateStopView',
    data() {
        return {
            store,
            stop: '',
            stopImage: null,
            updateStopName: '',
            updateStopNotes: '',
            updateStopRating: '',
            updateStopLongitude: '',
            updateStopLatitude: '',
            updateDayId: '',
            updateStopImage: '',
            loading: false,
            errors: {},
            imagePreviewUrl: ''
        }
    },
    methods: {
        // current data
        async getCurrentStop(id) {
            await axios.get(store.baseApiUrl + 'stop' + id).then(response => {
                // console.log(response.data.stop[0]);
                this.stop = ''
                this.stopImage = null
                this.updateDayId = ''
                this.updateStopName = ''
                this.updateStopLongitude = ''
                this.updateStopLatitude = ''
                this.updateStopImage = ''
                this.updateStopNotes = ''
                this.updateStopRating = ''
                this.stop = response.data.stop[0]
                this.stopImage = this.stop.image
                this.updateDayId = this.stop.day_id
                this.updateStopName = this.stop.name
                this.updateStopLongitude = this.stop.position_longitude
                this.updateStopLatitude = this.stop.position_latitude
                this.updateStopImage = this.stop.image
                this.updateStopNotes = this.stop.notes
                this.updateStopRating = this.stop.rating
            })
        },
        // map functions
        addMap() {
            const mapSm = tt.map({
                key: store.mapKey,
                container: 'map-sm',
            })
            const mapLg = tt.map({
                key: store.mapKey,
                container: 'map-lg',
            })

            let position = [this.updateStopLongitude, this.updateStopLatitude]
            let markerSm = new tt.Marker().setLngLat(position).addTo(mapSm)
            let popup = new tt.Popup().setText(this.updateStopName)
            markerSm.setPopup(popup)
            mapSm.setZoom(8)
            mapSm.setCenter(position)
            let markerLg = new tt.Marker().setLngLat(position).addTo(mapLg)
            markerLg.setPopup(popup)
            mapLg.setZoom(8)
            mapLg.setCenter(position)

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
            var ttSearchBoxSm = new SearchBox(services, options)
            mapSm.addControl(ttSearchBoxSm, "top-left")
            ttSearchBoxSm.updateOptions({
                minNumberOfCharacters: 3,
                labels: {
                    placeholder: "Where are you going*?",
                },
            })
            var ttSearchBoxLg = new SearchBox(services, options)
            mapLg.addControl(ttSearchBoxLg, "top-left")
            ttSearchBoxLg.updateOptions({
                minNumberOfCharacters: 3,
                labels: {
                    placeholder: "Where are you going*?",
                },
            })

            ttSearchBoxSm.on("tomtom.searchbox.resultselected", this.handleResultSelection)
            ttSearchBoxSm.on("tomtom.searchbox.resultfocused", this.handleResultSelection)
            ttSearchBoxLg.on("tomtom.searchbox.resultselected", this.handleResultSelection)
            ttSearchBoxLg.on("tomtom.searchbox.resultfocused", this.handleResultSelection)

            let map = [mapSm, mapLg]
            return map
        },
        handleResultSelection(e) {
            // console.log("selected", e);

            let map = this.addMap()

            let mapSm = map[0]
            let mapLg = map[1]

            let result = e.data.result

            if (result.type === 'POI') {
                this.updateStopName = result.poi.name
            } else {
                this.updateStopName = result.address.freeformAddress
            }

            let position = [result.position.lng, result.position.lat]
            let markerSm = new tt.Marker().setLngLat(position).addTo(mapSm)
            let markerLg = new tt.Marker().setLngLat(position).addTo(mapLg)
            let popup = new tt.Popup().setText(this.newStopName)
            markerSm.setPopup(popup)
            markerLg.setPopup(popup)
            mapSm.setZoom(8)
            mapSm.setCenter(position)
            mapLg.setZoom(8)
            mapLg.setCenter(position)

            this.updateStopLongitude = result.position.lng
            this.updateStopLatitude = result.position.lat
        },
        // update axios call
        updateStop() {

            if (typeof this.updateStopImage === "string") {
                this.updateStopImage = {}
            }

            const data = {
                day_id: this.updateDayId,
                name: this.updateStopName,
                position_longitude: this.updateStopLongitude.toString(),
                position_latitude: this.updateStopLatitude.toString(),
                image: this.updateStopImage,
                notes: this.updateStopNotes,
                rating: this.updateStopRating,
            }
            // console.log(data);

            this.loading = true

            axios.post(store.baseApiUrl + 'update-stop/' + this.$route.params.id, data, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                if (response.data.success) {
                    this.updateDayId = ''
                    this.updateStopName = ''
                    this.updateStopLongitude = ''
                    this.updateStopLatitude = ''
                    this.updateStopImage = ''
                    this.updateStopNotes = ''
                    this.updateStopRating = ''
                    this.errors = {}
                    // console.log(response.data.message);
                    this.$router.push({ name: 'day', params: { id: this.stop.day_id, date: this.$route.params.date } })
                }
            }).catch(error => {
                console.log(error);
                if (error) {
                    this.errors = error.response.data.errors
                }
            })

            this.loading = false
        },
        // image handler
        updateImage(e) {
            // console.log(e.target.files || e.dataTransfer.files);

            let image = e.target.files || e.dataTransfer.files

            this.imagePreviewUrl = URL.createObjectURL(image[0])

            this.updateStopImage = image[0]
            // console.log(this.updateStopImage);

        },
        deleteOldImage() {
            const data = {
                image: this.updateStopImage,
            }
            // console.log(data);

            this.loading = true

            axios.post(store.baseApiUrl + 'delete-stop-image/' + this.$route.params.id, data).then(response => {
                if (response.data.success) {
                    // console.log(response.data.message);
                    this.stopImage = null
                }
            }).catch(error => {
                console.log(error);
                if (error) {
                    this.errors = error.response.data.errors
                }
            })

            this.loading = false
        }
    },
    async mounted() {
        await this.getCurrentStop(this.$route.params.id)
        this.addMap()
    }
}
</script>

<template>
    <section id="update-stop">
        <div class="container">
            <div class="row">
                <div class="col m-2">
                    <!-- left col -->
                    <form @submit.prevent="updateStop()" enctype="multipart/form-data">
                        <div class="mm-3">
                            <label for="update-stop-title" class="form-label">Name / Location :</label>
                            <input type="text" class="form-control" name="update-stop-title" id="update-stop-title"
                                placeholder="Rome" v-model="updateStopName" />
                        </div>

                        <!-- map small col -->
                        <div class="col" id="map-sm-container">
                            <div id='map-sm'></div>
                        </div>

                        <div class="mb-3">
                            <label for="update-stop-description" class="form-label">Notes :</label>
                            <textarea class="form-control" name="update-stop-description" id="update-stop-description"
                                rows="3" v-model="updateStopNotes"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="update-stop-image" class="form-label">Image :</label>
                            <input type="file" class="form-control" name="update-stop-image" id="update-stop-image"
                                @change="updateImage">
                        </div>

                        <div class="images mb-3">
                            <div class="d-flex justify-content-around">
                                <p>old</p>
                                <p>new</p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="image">
                                    <img :src="store.imageBaseUrl + stop.image" alt="" v-if="stopImage !== null">
                                    <img v-else src="/landscape-placeholder.svg" alt="">
                                </div>
                                <div class="image">
                                    <img :src="imagePreviewUrl" alt="" v-if="imagePreviewUrl">
                                    <img v-else src="/landscape-placeholder.svg" alt="">
                                </div>
                            </div>
                            <div class="w-50 text-center" v-if="stop.image">
                                <div class="btn btn-danger mt-2" v-html="store.trashIcon" @click="deleteOldImage()">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="update-stop-rating" class="form-label">Rating :</label>
                            <div class="d-flex justify-content-between">
                                <p>0</p>
                                <p v-for="n in 5">{{ n }}</p>
                            </div>
                            <input type="range" class="form-range" min="0" max="5" id="update-stop-rating"
                                v-model="updateStopRating">
                        </div>

                        <div class="my-3">
                            <button class="form-control" type="submit" :disabled="loading">
                                {{ loading ? 'Updating...' : 'Update stop' }}
                            </button>
                        </div>
                    </form>

                    <div class="errors text-danger m-3" v-if="Object.keys(this.errors).length !== 0">
                        <div v-for="error in errors">{{ error[0] }}</div>
                    </div>
                </div>
                <!-- right col -->
                <div class="col" id="map-lg-container">
                    <div id='map-lg'></div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.images {
    .image {
        border: 3px solid black;
        width: 49%;
        aspect-ratio: 1;
        text-align: center;

        img {
            height: 100%;
            width: 100%;
            object-fit: contain;
        }
    }
}
</style>