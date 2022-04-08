<script>
export default {
    data: () => ({
        deleting: false,
    }),
    methods: {
        deleteEntry(confirmation, url, options, delete_caption = 'Удалить') {
            return new Promise((resolve, reject) => {
                this.$dialog.show(confirmation, null, 'red', [
                    this.$dialog.button('yes', delete_caption, 'blue'),
                    this.$dialog.button('no', 'Отмена', 'default'),
                ], 'left').then(result => {
                    if (result === 'yes') {
                        // delete logic
                        this.deleting = true;
                        axios.post(url, options)
                            .then((response) => {
                                this.deleting = false;
                                this.$toast.success(response.data.message, 5000);
                                resolve(response);
                            })
                            .catch(error => {
                                this.deleting = false;
                                this.$toast.error(error.response.data.message, 5000);
                                reject();
                            });
                    }
                });
            });
        }
    }
}
</script>
