<template>
  <div>
    <a v-if="company == false" :href="link">
      <div class="property-card">
        <div class="property-preview-image">
          <img v-if="imagePath" class="main-image" :src="imagePath">
        </div>
        <div class="property-info">
          <div class="contact-info">
            <h3 :class="['title', 'title-'+membership]">{{ name }}</h3>
            <div class="contacts">
              <a v-if="phone" :href="'tel:'+phone">
                <img :src="'/images/icons/ico_tel.jpg'">
              </a>
              <a v-if="email" :href="'mailto:'+email">
                <img :src="'/images/icons/ico_mensaje.jpg'">
              </a>        
            </div>        
          </div>

          <div class="property-description">
            <p v-if="description" style="color: #4a4a4a;">{{ description|truncate(155) }}</p>      
          </div>
        </div>    
      </div>
    </a>
    <div v-else class="property-card" @click="filterAgent()">
        <div class="property-preview-image">
          <img v-if="imagePath" class="main-image" :src="imagePath">
        </div>
        <div class="property-info">
          <div class="contact-info">
            <h3 :class="['title', 'title-'+membership]">{{ name }}</h3>
            <div class="contacts">
              <a v-if="phone" :href="'tel:'+phone">
                <img :src="'/images/icons/ico_tel.jpg'">
              </a>
              <a v-if="email" :href="'mailto:'+email">
                <img :src="'/images/icons/ico_mensaje.jpg'">
              </a>        
            </div>        
          </div>

          <div class="property-description">
            <p v-if="description">{{ description|truncate(155) }}</p>      
          </div> 

          <div style="text-align: center;">
            <a :href="link" :class="'button is-outlined ver-mas is-'+membership">
              VER MAS
            </a>     
          </div>
        </div>    
      </div>
  </div>
  
  
</template>

<script>
const imagesDir = document.getElementById('images-dir').value;

export default {
   name: 'Agent',
   props: {
     id: Number,
     agent_id: Number,
     imagePath: String,
     membership: String,
     name: String,
     phone: String,
     email: String,
     cell_phone: String,
     description: String,
     item: Number,
     company:{
       type: Boolean,
       default: false
     }
   },
   data(){
     return{
     }
   },
   computed:{
     link(){
       if(this.company){
         return '/users/agente/' + this.agent_id;
         
       } else {
         return '/agentes/' + this.id;
       }
     }
   },
   filters: {
     truncate: function(value, length) {
       return (value.length > length) ? value.substr(0, length-1) + "..." : value;
     }
   },
   methods: {
    filterAgent() {
      this.$emit('filter', this.id)
    },
   },
   mounted() {

   }
 }
</script>

<style lang="scss" scoped>
  .property-card {
    padding: 5px;
    padding-bottom: 0;
  }
  .property-card .property-info {
    display: block;    
    padding: 0.5rem 0 0;
  }
  .contact-info {
    width: 100%;
    height: 33px;
  }
  .contacts {
    float: right;
  }

  .title {
    float: left;
    color: white;
    font-size: 12px;
    width: 70%;
    padding: 8px;
    padding-left: 16px;
    height: 33px;
    margin-bottom: 0;

      &-basic {
        background-color: rgba(0, 162, 255);
      }

      &-select {
        background-color: rgba(149, 122, 250);
      }

      &-premium {
        background-color: rgba(249, 128, 247);
      }
    }

  .property-description {
    padding: 1rem 0.5rem;
  }
  .property-description p {
    border-bottom: none !important;
    padding-bottom: 0 !important;
  }
  .ver-mas {
    transform: translateY(50%);
    color: #04d5f7;
    border-color: #04d5f7;
  }

</style>