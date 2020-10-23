<template>
    <tr>
        <td>
            <img :src="info.photo.path" style="width:100px;height:75px;"/>
        </td>
        <td class=" is-size-7">
            <div><b>{{info.type_name}}</b></div>
            <div>{{info.list_name}}</div>
        </td>
        <td class=" is-size-7">
            <div>{{info.description}}</div>
        </td>
        <td class="has-text-centered" style="vertical-align:middle">
            <a @click="$router.push('/service/'+ info.id)" style="padding: 5px; color: #08b7ff;"><span class="tooltip" data-tooltip="Ver Servicio"><i class="fa fa-eye fa-lg"></i></span></a>
        </td>
    </tr>
</template>

<script>

const imagesDir = document.getElementById('images-dir').value;
export default {
    name: 'Services',
    props: {
        info: Object,
    },
    data() {
        return {
            imagesDir: imagesDir
        }
    },
}
</script>