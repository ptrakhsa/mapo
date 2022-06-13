Vue.component('event-detail', {
    props: ['name', 'description', 'photo', 'content', 'date', 'category', 'link', 'organizer', 'location', 'hideDetail'],
    template: `
    <div class="card px-4 py-4 mx-3 my-3">
    <div class="d-flex justify-content-end"
        style="position: absolute;right:0px;top:0px;">
        <slot></slot>
    </div>

    <div class="card-content">
        <h3 v-text="name"></h3>
        <span class="badge bg-light-primary mb-3" v-text="category"></span>

        <img :src="photo" class="img-fluid w-100" alt="">
        <div class="card-body">
            <p class="text-subtitle" v-text="description"></p>

            <div class="mb-3" v-html="content"></div>
            <small>
                <span class="fa-fw select-all fas"></span>
                <span v-text="date"></span>
            </small>
            <br>
            <small>
                <span class="fa-fw select-all fas"></span>
                <span v-text="location"></span>
            </small>
            <br>
            
        </div>

        <div class="card-footer d-flex justify-content-between">
            <span v-text="organizer"></span>
            <a target="_blank" :href="link" class="btn btn-primary" >Gmaps route</a>
        </div>
    </div>
</div>
    `
})