window.Open = () => {
    return {
        opened: false,
        toggleOpen() {
            this.opened = !this.opened
            console.log('open')
        }
    }
}