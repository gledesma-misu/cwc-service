<template>
    <div class="dropdown mx-2">
        <a href="#" class="text-secondary" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-bell" id="notification-icon"></i>
            <span id="notification-count" v-if="unread_notifications.length > 0">{{ unread_notifications.length
                }}</span>
        </a>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
            <li v-if="unread_notifications.length > 0">
                <a class="dropdown-item" href="#" @click.prevent="markNotificationAsRead({ id: 0 })">
                    Mark all as read!
                </a>
            </li>
            <li v-for="(unread, index) in unread_notifications" :key="index">
                <a class="dropdown-item" href="#" @click.prevent="markNotificationAsRead(unread)">
                    {{ unread.data.request.description }} - {{ unread.data.message }}
                    <p>{{ $filter.myDate(unread.created_at) }}</p>
                </a>
            </li>
            <li v-if="unread_notifications.length == 0">
                <a class="dropdown-item" href="#">
                    No new notifications
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="#" @click.prevent="getAllNotifications(unread)">
                    Show All Notifications!
                </a>
            </li>

        </ul>
    </div>

    <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        All Notifications
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between" v-if="all_notifications.length > 0">
                        <button type="button" class="btn btn-danger btn-block" @click.prevent="clearAllNotifications()">
                            Clear All Notifications</button>
                    </div>

                    <div class="card my-2" v-for="(all, index) in all_notifications" :key="index">
                        <div class="card-body">
                            <span> {{ all.data.request.description }} - {{ all.data.message }}</span><br>
                            <span>{{ $filter.myDate(all.created_at) }}</span>
                        </div>
                    </div>
                    <div class="card my-2" v-if="all_notifications.length == 0">
                        <div class="card-body">
                            <span>No notifications yet!</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
<script>

import { Modal } from "bootstrap";
export default {
    data() {
        return {
            exampleModal: null,
        }
    },
    computed: {
        unread_notifications() {
            return this.$store.getters.unread_notifications
        },
        all_notifications() {
            return this.$store.getters.all_notifications
        }
    },
    mounted() {
        // this.$store.dispatch('getUnreadNotifications');
        setTimeout(() => {
            this.listenToNotifications();
        }, 300);
    },
    methods: {
        markNotificationAsRead(unread) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to mark notification as read!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, mark it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$store.dispatch('markNotificationAsRead', unread)
                }
            })
        },
        clearAllNotifications() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to clear all notifications!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, clear it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$store.dispatch('clearAllNotifications')
                }
            })
        },
        getAllNotifications() {
            this.$store.dispatch('getAllNotifications');
            this.notificationModal = new Modal(document.getElementById("notificationModal"), { keyboard: false });
            this.notificationModal.show();
            // $('#notificationModal').modal('show')
        },
        listenToNotifications() {
            Echo.channel(`notification`).listen('NotificationEvent', () => {
                this.$store.dispatch('getUnreadNotifications');
            })
        }

    }
}
</script>

<style scoped>
#notification-icon {
    font-size: 25px;
    line-height: 30px;
}

#notification-count {
    text-align: center;
    position: absolute;
    top: -6px;
    min-width: 18px;
    min-height: 19px;
    border-radius: 50%;
    background-color: red;
    color: #fff;
    line-height: 19px;
    font-family: sans-serif;
}
</style>