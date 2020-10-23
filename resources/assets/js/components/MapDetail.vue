<template>
  <div class="map-details" ref="mapDetail">
    <div class="map-container">
      <div v-bind:id="map_id"></div>
    </div>
    <div class="filter-wrapper" v-show="canLoadMap">
      <div class="filter-container">
        <div class="filter search">
          <header class="filter-header">
            <div class="icon-wrapper">
              <img :src="imgDir + '/icons/lupa.png'" alt="lupa" width="20px" height="auto">
            </div>
            <input type="search" class="input" placeholder="Busca tu zona de ínteres">
          </header>
        </div>
        <div
          :class="'filter place ' + (markers.length > 0 ? '' : 'disabled empty') "
          v-for="(markers, place_type) in placesMarkers"
          :key="place_type"
        >
          <header class="filter-header">
            <div class="switch">
              <label class="radio">
                <input type="checkbox" :name="'checkBox-'+place_type" :value="place_type" checked>
              </label>
            </div>
            <div class="icon-wrapper">
              <img
                :src="placeTypeData[place_type].icon.url"
                :alt="place_type+'-icon'"
                height="24px"
                width="auto"
              >
            </div>
            <div class="header-title">{{ placeTypeData[place_type].label }}</div>
            <a href="#" aria-label="more options" class="toggle" onclick="return false;">
              <span class="icon">
                <i class="fa fa-angle-down" aria-hidden="true"></i>
              </span>
            </a>
          </header>
          <div class="filter-content">
            <ul class="places">
              <li
                class="place-item"
                v-for="(marker, index) in markers"
                :key="marker.more_data.id"
                v-on:click="findMarker(place_type, index)"
              >
                <div class="name">{{ marker.more_data.name }}</div>
                <div class="distance">{{ marker.more_data.distance.toFixed(2)}} m</div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="is-hidden">{{test}}</div>
  </div>
</template>
<script>
var GoogleMapsLoader = require("google-maps");

var MapDetail,
  map,
  infoWindow,
  services = {};

export default (MapDetail = {
  name: "MapDetail",
  props: {
    property_location: {
      type: Object
    },
    map_id: {
      type: String,
      default: "map"
    },
    imgDir: {
      type: String,
      default: document.getElementById("images-dir").getAttribute("value")
    }
  },
  data: function() {
    return {
      config: {
        places: {
          types: ["hospital", "school", "train_station", "bus_station"],
          radio: 10000 // In meters
        },
        markers_to_order: []
      },
      placeTypeData: {
        hospital: {
          label: "Hospitales",
          icon: {
            url: this.imgDir + "/icons/map_hospital.png"
          }
        },
        school: {
          label: "Escuelas",
          icon: {
            url: this.imgDir + "/icons/map_escuela.png"
          }
        },
        train_station: {
          label: "Trenes",
          icon: {
            url: this.imgDir + "/icons/map_tren.png"
          }
        },
        bus_station: {
          label: "Buses",
          icon: {
            url: this.imgDir + "/icons/map_bus.png"
          }
        }
      },
      test: "",
      propertyMarker: {},
      placesMarkers: {},
      map
    };
  },
  computed: {
    canLoadMap: function() {
      let position = this.propertyLocation;
      return (position.lat && position.lng) || false;
    },
    propertyLocation: function() {
      let vm = this;

      let position = {
        lat: null,
        lng: null
      };

      position.lat = parseFloat(vm.property_location.lat);
      position.lng = parseFloat(vm.property_location.lng);

      return typeof position.lat != "number" || typeof position.lng != "number"
        ? {}
        : position;
    }
  },
  watch: {
    property_location: function(oldV, newV) {
      typeof map == "undefined" ? this.loadMap() : this.reloadMap();
    }
  },
  beforeCreate: function() {
    // Google Maps Loader Config
    GoogleMapsLoader.KEY = "AIzaSyDTKRiKb5oaS7Z13QezK4K0V9XQI99UHiI";
    GoogleMapsLoader.LIBRARIES = ["places"];
  },
  mounted: function() {
    let vm = this;
  },
  beforeUpdate: function() {
    let vm = this;

    vm.config.markers_to_order = vm.config.markers_to_order.filter(
      place_type => {
        vm.placesMarkers[place_type] = orderArray(vm.placesMarkers[place_type]);

        return false;
      }
    );
  },
  updated: function() {
    let vm = this;

    // Click on Filter Header Checkbox Wrapper
    vm.$refs["mapDetail"].querySelectorAll(".switch").forEach(el =>
      el.addEventListener("click", e => {
        e.stopPropagation();
      })
    );

    // Click on Header Checkbox
    vm.$refs["mapDetail"].querySelectorAll(".switch input").forEach(
      el =>
        (el.onclick = function() {
          vm.togglePlaceMarkers(el.getAttribute("value"), el.checked);

          // Toggle disabled class to the filter
          let filter;
          if ((filter = findAncestor(el, "filter"))) {
            if (filter.classList.value.match("empty")) {
              e.preventDefault();
              return false;
            }
            if (!el.checked) {
              filter.classList.remove("open");
              filter.classList.add("disabled");
            } else {
              filter.classList.remove("disabled");
            }
          }
        })
    );

    // Click on Filter Header
    vm.$refs["mapDetail"]
      .querySelectorAll(".filter.place .filter-header")
      .forEach(
        el =>
          (el.onclick = () => {
            el.parentElement.classList.toggle("open");
          })
      );
  },
  methods: {
    loadMap: function() {
      let vm = this;

      if (!vm.canLoadMap) return false;

      GoogleMapsLoader.load(function() {
        vm.initMap();

        /* Event Listeners */

        // On searching
        vm.$refs["mapDetail"]
          .querySelector(".filter.search input")
          .addEventListener("keyup", e => vm.findPlace(e.target.value));
      });
    },
    reloadMap: function() {
      let vm = this;

      if (!vm.canLoadMap) return false;

      map.setCenter(vm.propertyLocation);

      vm.createPropertyMarker();

      vm.findNearPlaces();
    },
    initMap: function() {
      let vm = this;

      // Create the map
      map = new google.maps.Map(document.getElementById(vm.map_id), {
        center: vm.propertyLocation,
        zoom: 16,
        // Controls
        zoomControl: true,
        mapTypeControl: false,
        streetViewControl: false,
        rotateControl: false,
        fullscreenControl: false
      });

      // Style map
      styleMap();

      // Initialize Filter Control
      this.filterControl();

      vm.createPropertyMarker();

      // Init Info Window
      infoWindow = new google.maps.InfoWindow({
        content: "",
        maxWidth: 200
      });

      vm.findNearPlaces();
    },
    findNearPlaces: function() {
      let vm = this;

      // Initialize places markers base on place type
      for (let i = 0; i < this.config.places.types.length; i++) {
        vm.placesMarkers[this.config.places.types[i]] = [];
      }

      // Init services
      services.places = new google.maps.places.PlacesService(map);

      // Find nearby places to property
      for (let p = 0; p < vm.config.places.types.length; p++) {
        services.places.nearbySearch(
          {
            location: vm.propertyLocation,
            //rankBy: google.maps.places.RankBy.DISTANCE,
            type: vm.config.places.types[p],
            radius: vm.config.places.radio
          },
          function(results, status, pagination) {
            if (status == google.maps.places.PlacesServiceStatus.OK) {
              let marker_type = undefined;
              for (let i = 0; i < results.length; i++) {
                let marker = vm.createPlaceMarker(results[i]);
                if (marker && typeof marker_type == "undefined") {
                  // New list of place_type to order
                  vm.config.markers_to_order.push(
                    (marker_type = marker.placeType)
                  );
                }
              }
            }
          }
        );
      }
    },
    createPropertyMarker: function() {
      let vm = this;
      // Create the Property Marker
      vm.propertyMarker = new google.maps.Marker({
        map: map,
        position: vm.propertyLocation,
        icon: {
          size: new google.maps.Size(100, 100),
          origin: new google.maps.Point(0, 0),
          anchor: new google.maps.Point(0, 75),
          scaledSize: new google.maps.Size(75, 75),
          url: vm.imgDir + "/icons/zona.png"
        }
      });
    },
    createPlaceMarker: function(place) {
      let vm = this;
      let distanceToProperty = distanceTo(
        vm.propertyMarker.getPosition(),
        place.geometry.location
      );

      // Filter by Distance
      if (vm.config.places.radio && distanceToProperty > vm.config.places.radio)
        return false;

      vm.test = place.name;

      let marker = new google.maps.Marker({
        position: place.geometry.location,
        map: map
      });

      // set additional data
      marker.more_data = {
        id: place.id,
        address: place.vicinity,
        name: place.name,
        distance: distanceToProperty
      };

      // set place type to the marker
      place.types.forEach(function(type) {
        if (typeof marker.placeType == "undefined") {
          marker.placeType =
            vm.config.places.types.indexOf(type) == -1 ? undefined : type;
        }
      });

      // Filter markers without icons
      if (typeof marker.placeType == "undefined") {
        // Delete marker from map
        marker.setMap(null);

        return false;
      }

      // Set the marker icon
      vm.setMarkerIcon(marker, place);

      vm.placesMarkers[marker.placeType].push(marker);

      marker.addListener("click", function() {
        infoWindow.setContent(
          this.more_data.name + "<br>" + this.more_data.address
        );
        infoWindow.open(map, this);
      });

      return marker;
    },
    findMarker: function(place_type, number) {
      if (typeof this.placesMarkers[place_type][number] == "undefined")
        return false;

      let marker = this.placesMarkers[place_type][number];

      map.panTo({ lat: marker.position.lat(), lng: marker.position.lng() });

      // trigger click on marker
      new google.maps.event.trigger(
        this.placesMarkers[place_type][number],
        "click"
      );
    },
    setMarkerIcon: function(marker, place) {
      let vm = this;
      let icon = {
        size: new google.maps.Size(20, 20),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(0, 20),
        scaledSize: new google.maps.Size(20, 20)
      };

      // Set Icon URL
      if (typeof vm.placeTypeData[marker.placeType].icon != "undefined") {
        icon.url = vm.placeTypeData[marker.placeType].icon.url;
      } else {
        icon.url = place.icon;
      }

      marker.setIcon(icon);
    },
    togglePlaceMarkers: function(place_type, show) {
      // If markers of the place type  doesn't exists
      if (typeof this.placesMarkers[place_type] == "undefined") return;

      this.placesMarkers[place_type].forEach(marker =>
        marker.setMap(show ? map : null)
      );
    },
    filterControl: function() {
      let vm = this;
      // Manage if the filter control has to be inserted or taken out of map
      const managerFilterControl = function(vm) {
        // Add filter to the map
        let filter_wrapper = vm.$refs["mapDetail"].querySelector(
          ".filter-wrapper"
        );

        if (window.innerWidth > 768) {
          if (map.controls[google.maps.ControlPosition.TOP_LEFT].length == 0) {
            filter_wrapper.classList.add("on-map");

            map.controls[google.maps.ControlPosition.TOP_LEFT].push(
              filter_wrapper
            );
          }
        } else {
          if (map.controls[google.maps.ControlPosition.TOP_LEFT].length > 0) {
            filter_wrapper = map.controls[
              google.maps.ControlPosition.TOP_LEFT
            ].pop();
            filter_wrapper.classList.remove("on-map");
            filter_wrapper.removeAttribute("style");

            vm.$refs["mapDetail"].appendChild(filter_wrapper);
          }
        }
      };

      managerFilterControl(vm);
      window.addEventListener("resize", () => {
        managerFilterControl(vm);
      });
    },
    findPlace: function(q) {
      let vm = this;

      q = q.toLowerCase();

      vm.$refs["mapDetail"]
        .querySelectorAll(".filter.place")
        .forEach(places => {
          let matches = 0;

          // For each place
          places.querySelectorAll(".place-item").forEach(place => {
            if (
              place
                .querySelector(".name")
                .textContent.toLowerCase()
                .match(q)
            ) {
              place.classList.remove("is-hidden");
              matches++;
            } else {
              place.classList.add("is-hidden");
            }
          });

          // If there's not value, close all places-container.
          if (q == "") {
            places.classList.remove("open");
            places.classList.remove("is-hidden");
          } else {
            if (matches == 0) {
              places.classList.add("is-hidden");
              places.classList.remove("open");
            } else {
              places.classList.add("open");
              places.classList.remove("is-hidden");
            }
          }
        });
    }
  }
});

function styleMap() {
  let styledMap = new google.maps.StyledMapType(
    [
      {
        featureType: "poi.attraction",
        stylers: [
          {
            visibility: "off"
          }
        ]
      },
      {
        featureType: "poi.business",
        stylers: [
          {
            visibility: "off"
          }
        ]
      },
      {
        featureType: "poi.school",
        elementType: "geometry",
        stylers: [
          {
            visibility: "off"
          }
        ]
      },
      {
        featureType: "poi.sports_complex",
        stylers: [
          {
            visibility: "simplified"
          }
        ]
      },
      {
        featureType: "transit.station",
        stylers: [
          {
            visibility: "off"
          }
        ]
      },
      {
        featureType: "transit.station.bus",
        stylers: [
          {
            visibility: "off"
          }
        ]
      },
      {
        featureType: "transit.station.rail",
        stylers: [
          {
            visibility: "off"
          }
        ]
      }
    ],
    { name: "Uhomie Reference Map" }
  );

  // Set style to the map
  map.mapTypes.set("styled_map", styledMap);
  map.setMapTypeId("styled_map");
}

/**
 *  Retrieves distance in meters of two points.
 *   Need a latitude & longitude of each point.
 */
function distanceTo(A, B) {
  let rad = function(x) {
    return (x * Math.PI) / 180;
  };

  let R = 6378137;

  let dLat = rad(B.lat() - A.lat());
  let dLng = rad(B.lng() - A.lng());

  let a =
    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
    Math.cos(rad(A.lat())) *
      Math.cos(rad(B.lat())) *
      Math.sin(dLng / 2) *
      Math.sin(dLng / 2);
  let c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  let d = R * c;

  return d;
}

function findAncestor(el, cls) {
  while ((el = el.parentElement) && !el.classList.contains(cls));

  return el;
}

function orderArray(list) {
  for (let p = 1; p < list.length; p++) {
    let aux = list[p];
    let j = p - 1;

    while (j >= 0 && aux.more_data.distance < list[j].more_data.distance) {
      list[j + 1] = list[j];
      j--;
    }
    list[j + 1] = aux;
  }
  return list;
}
</script>
 