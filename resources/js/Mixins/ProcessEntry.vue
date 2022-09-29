<script>
export default {
    data: () => ({
        processing: false,
    }),
    methods: {
        processEntry(confirmation, caption, url, options) {
            return new Promise((resolve, reject) => {
                this.$dialog.show(confirmation, null, 'red', [
                    this.$dialog.button('yes', caption, 'blue'),
                    this.$dialog.button('no', 'Отмена', 'default'),
                ], 'left').then(result => {
                    if (result === 'yes') {
                        // delete logic
                        this.processing = true;
                        axios.post(url, options)
                            .then((response) => {
                                this.processing = false;
                                this.$toast.success(response.data.message, 5000);
                                resolve(response);
                            })
                            .catch(error => {
                                this.processing = false;
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
