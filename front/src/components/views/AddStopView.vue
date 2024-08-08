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
            newStopPositionLongitude: '',
            newStopPositionLatitude: '',
            newStopImage: {},
            newStopNotes: '',
            newStopRating: '',
            loading: false,
            errors: '',
            imagePreview: ''
        }
    },
    methods: {
        createNewStop() {
            const data = {
                day_id: this.$route.params.id,
                name: this.newStopName,
                position_longitude: this.newStopPositionLongitude.toString(),
                position_latitude: this.newStopPositionLatitude.toString(),
                image: this.newStopImage,
                notes: this.newStopNotes,
                rating: this.newStopRating,
            }
            // console.log(data);

            this.loading = true

            axios.post(store.baseApiUrl + 'new-stop', data, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                if (response.data.success) {
                    this.newStopName = ''
                    this.newStopPositionLongitude = ''
                    this.newStopPositionLatitude = ''
                    this.newStopImage = ''
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
        // image handler
        newImage(e) {
            // console.log(e.target.files || e.dataTransfer.files);

            let image = e.target.files || e.dataTransfer.files

            this.imagePreview = URL.createObjectURL(image[0])

            this.newStopImage = image[0]
        },
        // map
        addMap() {
            const mapSm = tt.map({
                key: store.mapKey,
                container: 'map-sm',
            })
            const mapLg = tt.map({
                key: store.mapKey,
                container: 'map-lg',
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
                this.newStopName = result.poi.name
            } else {
                this.newStopName = result.address.freeformAddress
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
    <section id="add-stop">
        <div class="container">
            <div class="row">
                <div class="col m-2">
                    <!-- left col -->
                    <form @submit.prevent="createNewStop()" enctype="multipart/form-data">
                        <div class="mm-3">
                            <label for="new-stop-title" class="form-label">Name / Location :</label>
                            <input type="text" class="form-control" name="new-stop-title" id="new-stop-title"
                                placeholder="Rome" v-model="newStopName" />
                        </div>

                        <!-- map small col -->
                        <div class="col" id="map-sm-container">
                            <div id='map-sm'></div>
                        </div>

                        <div class="mb-3">
                            <label for="new-stop-description" class="form-label">Notes :</label>
                            <textarea class="form-control" name="new-stop-description" id="new-stop-description"
                                rows="3" v-model="newStopNotes"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="new-stop-image" class="form-label">Image :</label>
                            <input type="file" class="form-control" name="new-stop-image" id="new-stop-image"
                                @change="newImage">
                        </div>

                        <div class="new-image mb-3">
                            <div class="d-flex justify-content-between w-50">
                                <div class="image">
                                    <img :src="imagePreview" alt="" v-if="imagePreview">
                                    <img v-else src="/landscape-placeholder.svg" alt="">

                                </div>
                                <div class="btn btn-danger mx-3" v-html="store.trashIcon"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="new-stop-rating" class="form-label">Rating :</label>
                            <div class="d-flex justify-content-between">
                                <p>0</p>
                                <p v-for="n in 5">{{ n }}</p>
                            </div>
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
                <!-- right col -->
                <div class="col" id="map-lg-container">
                    <div id='map-lg'></div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.new-image {
    .image {
        border: 3px solid black;
        height: 20vh;
        aspect-ratio: 1;
        text-align: center;

        img {
            height: 100%;
            width: 100%;
            object-fit: contain;
        }
    }

    .btn {
        height: fit-content;
    }
}
</style>