Vue.component('event-card', {
    props: ['name', 'description', 'photo', 'date', 'category'],
    template: `<div class="card mt-5 my-3 mx-4" style="max-height: 260px;cursor: pointer;">

                    <div class="row">

                        <div class="col-md-4">
                            <img :src="photo" class="img-fluid rounded-start h-100"
                            style="object-fit: contain;max-height: 260px">
                        </div>

                        <div class="col-md-8">
                            <div class="card-content">
                                <div class="card-body">
                                    <h4 class="card-title" v-text="name"></h4>
                                    <span class="badge bg-light-primary mb-3" v-text="category"></span>
                                    <p v-text="description" class="card-text" style="overflow:hidden;text-overflow:ellipsis;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;"></p>
                                    <p class="card-text">
                                        <small class="text-muted" v-text="date"></small>
                                    </p>
                                </div>
                            </div>
                        </div>
                </div>
        </div>`
})