<template>
	<section id="galeria" class="container is-fluid">
		<div class="tile is-ancestor">
			<div class="tile is-parent">
		    <article
		    	class="tile is-child has-background-image"
		    	:style="{ backgroundImage: 'url('+photo(0).src+')'}"
		    	>
		    </article>
		  </div>
		  <div 
		  	v-if="photosLength > 1"
		  	class="tile is-vertical is-6">
		    <div class="tile">
		      <div class="tile is-parent is-vertical">
		      	<!-- segunda -->
		        <article
		        	:style="{ backgroundImage: 'url('+photo(1).src+')'}"
		        	class="tile is-child has-background-image"
		        	></article>
		        <article 
		        	v-if="photosLength > 2"
		        	class="tile is-child has-background-image"
		        	:style="{ backgroundImage: 'url('+photo(2).src+')'}"
		        	></article>
		      </div>
		      <div 
		      	v-if="photosLength > 3"
		      	class="tile is-parent">
		        <article 
		        	class="tile is-child has-background-image"
		        	:style="{ backgroundImage: 'url('+photo(3).src+')'}"
		        	></article>
		      </div>
		    </div>
		  </div>		  
		</div>

		<div 
			v-if="photosLength > 4"
			class="tile is-ancestor is-hidden-mobile">
		  <div class="tile is-parent">
		    <article 
		    	class="tile is-child box has-background-image"
		    	:style="{ backgroundImage: 'url('+photo(4).src+')'}"
		    	>
		    </article>
		  </div>
		  <div 
		  	v-if="photosLength > 5"
		  	class="tile is-parent">
		    <article 
		    	class="tile is-child box has-background-image"
		    	:style="{ backgroundImage: 'url('+photo(5).src+')'}"
		    	>
		    </article>
		  </div>
		  <div 
		  	v-if="photosLength > 6"
		  	class="tile is-parent">
		    <article 
		    	class="tile is-child box has-background-image"
		    	:style="{ backgroundImage: 'url('+photo(6).src+')'}"
		    	>
		    </article>
		  </div>
		  <div 
		  	v-if="photosLength > 7"
		  	class="tile is-parent">
		    <article 
		    	class="tile is-child box has-background-image"
		    	:style="{ backgroundImage: 'url('+photo(7).src+')'}"
		    	>
		    </article>
		  </div>
		  
		</div>
	</section>
</template>

<script>
const imagesDir = document.getElementById('images-dir').value;

export default {
  props: {
  	photos: {
      type: Array,
      required: true,
      default: function() {
        return [];
      }
    },
  },
  data: function () {
    return {
      imagesDir: imagesDir,
    }
  },
  mounted() {
  	console.log("hola:")
  	console.log(this.photos)
  	console.log(this.photosLength)
  },
  methods: {
		photo(position) {
    	return this.photos[position] ? this.photos[position] : null
    }
  },
  computed: {  	
    photosLength() {
    	return this.photos.length
    }
  }
}
</script>

<style scoped>
	.has-background-image {
		background-size: cover;
		background-position: center;
	}
	.tile .is-parent {
		min-height: 300px;
		padding: 0.5rem;
	}
	#galeria {
		margin-top: 2rem;
	}
</style>