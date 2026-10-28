<template>
    <!-- Окно показывается, только когда по каналу пришли данные о статье. -->
    <div v-if="article != null" class="push-modal" role="alert">
        <button type="button" class="push-close" @click="article = null">&times;</button>
        <div class="push-title">Добавлена новая статья</div>
        <a :href="`/articles/${article.id}`">{{ article.name }}</a>
    </div>
</template>

<script>
export default {
    data() {
        return { article: null };
    },

    created() {
        if (!window.Echo) {
            return;
        }

        /* Слушаем публичный канал test и событие App\Events\NewArticleEvent. */
        window.Echo.channel('test').listen('NewArticleEvent', (e) => {
            console.log(e.article);
            this.article = e.article;
        });
    },
};
</script>

<style>
.push-modal {
    position: fixed;
    right: 20px;
    bottom: 20px;
    z-index: 1000;
    max-width: 320px;
    padding: 15px 40px 15px 18px;
    background: #fff;
    border-left: 5px solid #1a1a2e;
    border-radius: 6px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
}

.push-title {
    font-weight: bold;
    margin-bottom: 6px;
}

.push-close {
    position: absolute;
    top: 6px;
    right: 10px;
    background: none;
    border: none;
    font-size: 22px;
    cursor: pointer;
    color: #888;
}
</style>
