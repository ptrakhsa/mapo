Vue.component('shimmer-card', {
    template: `<div id="shimmer-container" class="my-3 mx-4 px-3">
    <div id="shimmer-square" class="shimmer"></div>
        <div id="shimmer-content">
            <div id="shimmer-title" class="shimmer"></div>
            <div id="shimmer-desc">
                <div class="line shimmer" style="width: 100px;"></div>
                <div class="line shimmer line-width-100" style="width: 70px"></div>
                <div class="line shimmer line-width-70" style="width: 50px"></div>
                <div class="line shimmer line-width-50" style="width: 100px"></div>
            </div>
        </div>
    </div>`
})