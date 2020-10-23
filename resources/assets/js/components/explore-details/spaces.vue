<template>
	<section id="espacios" class="section">
    <div class="container">
			<h3 class="subtitle is-size-5 has-text-weight-bold" style="margin-bottom: 0;">
				Recorre esta propiedad
			</h3>

			<div class="columns is-multiline">
				<div 
					v-for="(p,i) in photos"
					:key="i"
					class="column is-3"
					:class="{ 'is-hidden-mobile': (i > (limitMobile-1) && !showMore), 'is-hidden-tablet': (i > (limitDesktop-1) && !showMore) }"
					>
					<img
						:src="p.src" :alt="p.name" />
					<h6 class="is-size-7 has-text-weight-normal">
						{{ p.name }}
					</h6>
				</div>
			</div>
			<button 
				v-if="photos.length > limitMobile"
				class="button is-outlined is-primary is-small"
				:class="{ 'is-hidden-tablet': photos.length <= limitDesktop }"
				>
				Mostrar todas las fotos
			</button>
		</div>
	</section>
</template>

<script>

export default {
  props: ['photos'],
  data: function () {
    return {
    	showMore: false,
      limitMobile: 2,
      limitDesktop: 8
    }
  },
  mounted() {
  	
  },
  methods: {

  },
  computed: {  	
    
  }
}
</script>

<style scoped>
	.columns {
		margin-top: 1rem;
	}
</style>