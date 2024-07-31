<script>
import tt from "@tomtom-international/web-sdk-maps"

export default {
    name: 'Map',
    props: {
        stops: Array
    },
    methods: {
        addMap() {
            tt.map({
                key: "koaCbZL6M2ThGOlvwAqsz9z3lopU60iG",
                container: "map",
            })
            this.stops.forEach(stop => {
                // console.log(stop.position_longitude, stop.position_latitude);
                let position = [stop.position_longitude, stop.position_latitude]
                let marker = new tt.Marker().setLngLat(position).addTo(map)
                let popup = new tt.Popup()
                marker.setPopup(popup)
            });
        }
    },
    mounted() {
        this.addMap()
    }
} 
</script>

<template>
    <div id='map'></div>
</template>

<style scoped>
#map {
    width: 100%;
    height: 75vh;
    margin: 1rem auto 0;
}
</style>