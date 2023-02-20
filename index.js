panel.plugin('jan-herman/page-builder', {
    fields: {
        pageBuilder: {
            extends: 'k-blocks-field',
            props: {
                cssClass: String
            },
            mounted() {
                if (this.cssClass) {
                    this.$el.classList.add(this.cssClass);
                }
            }
        }
    }
});
