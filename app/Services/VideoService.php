<?php

namespace App\Services;

use App\Enums\VoucherStatus;
use App\Http\Resources\VoucherResource;
use App\Models\Voucher;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class VideoService
{
    /**
     * Get all vouchers with optional filtering and pagination
     *
     * @param array $filters
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function get($channel): array
    {
        try {
            $json = '[
        {
            "id": 58,
            "uuid": "30eab5fa-f35c-40fb-822b-3b7a70ddfb4e",
            "shortUUID": "73m7cR8fiDeC3XLtWro7Zd",
            "url": "https://dob.media.fibodo.com/videos/watch/30eab5fa-f35c-40fb-822b-3b7a70ddfb4e",
            "name": "149 - Cycle - Hannah - 31mins",
            "category": {
                "id": 5,
                "label": "Sports"
            },
            "licence": {
                "id": 4,
                "label": "Attribution - Non Commercial"
            },
            "language": {
                "id": "en",
                "label": "English"
            },
            "privacy": {
                "id": 1,
                "label": "Public"
            },
            "nsfw": false,
            "truncatedDescription": null,
            "description": null,
            "isLocal": true,
            "duration": 1900,
            "aspectRatio": 1.7778,
            "views": 14,
            "viewers": 0,
            "likes": 0,
            "dislikes": 0,
            "thumbnailPath": "/lazy-static/thumbnails/07bfe394-ac13-42c3-b710-ce7ef566dedb.jpg",
            "previewPath": "/lazy-static/previews/834ad84e-4c44-4db0-931e-7cfa2928b07e.jpg",
            "embedPath": "/videos/embed/30eab5fa-f35c-40fb-822b-3b7a70ddfb4e",
            "createdAt": "2024-05-27T14:46:24.275Z",
            "updatedAt": "2025-06-03T10:34:23.451Z",
            "publishedAt": "2024-06-01T21:56:55.402Z",
            "originallyPublishedAt": null,
            "isLive": false,
            "account": {
                "id": 7,
                "displayName": "DoingOurBit",
                "name": "d_o_b",
                "url": "https://dob.media.fibodo.com/accounts/d_o_b",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/39a47004-7915-46ee-bc6d-d3638300f51d.jpg",
                        "createdAt": "2024-05-27T04:53:46.090Z",
                        "updatedAt": "2024-05-27T04:53:46.090Z"
                    },
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/49f68f0b-810a-407a-ba01-99f7b1b35a05.jpg",
                        "createdAt": "2024-05-27T04:53:46.173Z",
                        "updatedAt": "2024-05-27T04:53:46.173Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/6be0086f-1036-4546-8daf-e31c255a19f2.jpg",
                        "createdAt": "2024-05-27T04:53:46.170Z",
                        "updatedAt": "2024-05-27T04:53:46.170Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/4087d3ca-9e7c-46e5-a3ea-ca78e14affca.jpg",
                        "createdAt": "2024-05-27T04:53:46.167Z",
                        "updatedAt": "2024-05-27T04:53:46.167Z"
                    }
                ]
            },
            "channel": {
                "id": 12,
                "name": "johnson_digital2",
                "displayName": "Johnson Digital Video’s",
                "url": "https://dob.media.fibodo.com/video-channels/johnson_digital2",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/378ceeb5-7346-4dbb-b433-509ef44a4581.png",
                        "createdAt": "2024-05-27T04:57:17.681Z",
                        "updatedAt": "2024-05-27T04:57:17.681Z"
                    },
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/45c4827a-2dfc-41d3-83ff-de23948620b9.png",
                        "createdAt": "2024-05-27T04:57:17.708Z",
                        "updatedAt": "2024-05-27T04:57:17.708Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/9d9ae58a-5eff-42b7-8364-7e2cc10f45a1.png",
                        "createdAt": "2024-05-27T04:57:17.704Z",
                        "updatedAt": "2024-05-27T04:57:17.704Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/f6ac3eee-140c-4ee8-97d3-bc6a1b246dde.png",
                        "createdAt": "2024-05-27T04:57:17.701Z",
                        "updatedAt": "2024-05-27T04:57:17.701Z"
                    }
                ]
            }
        },
        {
            "id": 59,
            "uuid": "455324be-97de-4c9c-b325-0bd103bedd82",
            "shortUUID": "9yvA3xnv2enmVs7HufZX25",
            "url": "https://dob.media.fibodo.com/videos/watch/455324be-97de-4c9c-b325-0bd103bedd82",
            "name": "207 - Band - Faye - 24mins",
            "category": {
                "id": 5,
                "label": "Sports"
            },
            "licence": {
                "id": 4,
                "label": "Attribution - Non Commercial"
            },
            "language": {
                "id": "en",
                "label": "English"
            },
            "privacy": {
                "id": 1,
                "label": "Public"
            },
            "nsfw": false,
            "truncatedDescription": null,
            "description": null,
            "isLocal": true,
            "duration": 1406,
            "aspectRatio": 1.7778,
            "views": 16,
            "viewers": 0,
            "likes": 0,
            "dislikes": 0,
            "thumbnailPath": "/lazy-static/thumbnails/3269f474-e67a-4418-9c50-f03618db8e61.jpg",
            "previewPath": "/lazy-static/previews/1ad80847-c071-4819-82c3-af20d7c4fbab.jpg",
            "embedPath": "/videos/embed/455324be-97de-4c9c-b325-0bd103bedd82",
            "createdAt": "2024-05-27T15:40:17.678Z",
            "updatedAt": "2025-06-17T12:34:23.701Z",
            "publishedAt": "2024-06-01T21:55:03.219Z",
            "originallyPublishedAt": null,
            "isLive": false,
            "account": {
                "id": 7,
                "displayName": "DoingOurBit",
                "name": "d_o_b",
                "url": "https://dob.media.fibodo.com/accounts/d_o_b",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/39a47004-7915-46ee-bc6d-d3638300f51d.jpg",
                        "createdAt": "2024-05-27T04:53:46.090Z",
                        "updatedAt": "2024-05-27T04:53:46.090Z"
                    },
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/49f68f0b-810a-407a-ba01-99f7b1b35a05.jpg",
                        "createdAt": "2024-05-27T04:53:46.173Z",
                        "updatedAt": "2024-05-27T04:53:46.173Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/6be0086f-1036-4546-8daf-e31c255a19f2.jpg",
                        "createdAt": "2024-05-27T04:53:46.170Z",
                        "updatedAt": "2024-05-27T04:53:46.170Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/4087d3ca-9e7c-46e5-a3ea-ca78e14affca.jpg",
                        "createdAt": "2024-05-27T04:53:46.167Z",
                        "updatedAt": "2024-05-27T04:53:46.167Z"
                    }
                ]
            },
            "channel": {
                "id": 12,
                "name": "johnson_digital2",
                "displayName": "Johnson Digital Video’s",
                "url": "https://dob.media.fibodo.com/video-channels/johnson_digital2",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/378ceeb5-7346-4dbb-b433-509ef44a4581.png",
                        "createdAt": "2024-05-27T04:57:17.681Z",
                        "updatedAt": "2024-05-27T04:57:17.681Z"
                    },
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/45c4827a-2dfc-41d3-83ff-de23948620b9.png",
                        "createdAt": "2024-05-27T04:57:17.708Z",
                        "updatedAt": "2024-05-27T04:57:17.708Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/9d9ae58a-5eff-42b7-8364-7e2cc10f45a1.png",
                        "createdAt": "2024-05-27T04:57:17.704Z",
                        "updatedAt": "2024-05-27T04:57:17.704Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/f6ac3eee-140c-4ee8-97d3-bc6a1b246dde.png",
                        "createdAt": "2024-05-27T04:57:17.701Z",
                        "updatedAt": "2024-05-27T04:57:17.701Z"
                    }
                ]
            }
        },
        {
            "id": 71,
            "uuid": "9af0a094-425f-43c0-bcf9-33c651e2786d",
            "shortUUID": "k8GdKpCwm7jjtkmRfdCmRB",
            "url": "https://dob.media.fibodo.com/videos/watch/9af0a094-425f-43c0-bcf9-33c651e2786d",
            "name": "233 - Fifteen - Shinead - 28mins",
            "category": {
                "id": 5,
                "label": "Sports"
            },
            "licence": {
                "id": 4,
                "label": "Attribution - Non Commercial"
            },
            "language": {
                "id": "en",
                "label": "English"
            },
            "privacy": {
                "id": 1,
                "label": "Public"
            },
            "nsfw": false,
            "truncatedDescription": null,
            "description": null,
            "isLocal": true,
            "duration": 1676,
            "aspectRatio": 1.7778,
            "views": 18,
            "viewers": 0,
            "likes": 0,
            "dislikes": 0,
            "thumbnailPath": "/lazy-static/thumbnails/72415643-bce3-4f5c-befa-ef6b307bff29.jpg",
            "previewPath": "/lazy-static/previews/ea491130-e40f-41d9-adb8-e6e52ce20222.jpg",
            "embedPath": "/videos/embed/9af0a094-425f-43c0-bcf9-33c651e2786d",
            "createdAt": "2024-05-28T08:13:20.972Z",
            "updatedAt": "2025-04-21T14:34:22.401Z",
            "publishedAt": "2024-06-01T21:53:45.385Z",
            "originallyPublishedAt": null,
            "isLive": false,
            "account": {
                "id": 7,
                "displayName": "DoingOurBit",
                "name": "d_o_b",
                "url": "https://dob.media.fibodo.com/accounts/d_o_b",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/39a47004-7915-46ee-bc6d-d3638300f51d.jpg",
                        "createdAt": "2024-05-27T04:53:46.090Z",
                        "updatedAt": "2024-05-27T04:53:46.090Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/4087d3ca-9e7c-46e5-a3ea-ca78e14affca.jpg",
                        "createdAt": "2024-05-27T04:53:46.167Z",
                        "updatedAt": "2024-05-27T04:53:46.167Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/6be0086f-1036-4546-8daf-e31c255a19f2.jpg",
                        "createdAt": "2024-05-27T04:53:46.170Z",
                        "updatedAt": "2024-05-27T04:53:46.170Z"
                    },
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/49f68f0b-810a-407a-ba01-99f7b1b35a05.jpg",
                        "createdAt": "2024-05-27T04:53:46.173Z",
                        "updatedAt": "2024-05-27T04:53:46.173Z"
                    }
                ]
            },
            "channel": {
                "id": 12,
                "name": "johnson_digital2",
                "displayName": "Johnson Digital Video’s",
                "url": "https://dob.media.fibodo.com/video-channels/johnson_digital2",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/378ceeb5-7346-4dbb-b433-509ef44a4581.png",
                        "createdAt": "2024-05-27T04:57:17.681Z",
                        "updatedAt": "2024-05-27T04:57:17.681Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/f6ac3eee-140c-4ee8-97d3-bc6a1b246dde.png",
                        "createdAt": "2024-05-27T04:57:17.701Z",
                        "updatedAt": "2024-05-27T04:57:17.701Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/9d9ae58a-5eff-42b7-8364-7e2cc10f45a1.png",
                        "createdAt": "2024-05-27T04:57:17.704Z",
                        "updatedAt": "2024-05-27T04:57:17.704Z"
                    },
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/45c4827a-2dfc-41d3-83ff-de23948620b9.png",
                        "createdAt": "2024-05-27T04:57:17.708Z",
                        "updatedAt": "2024-05-27T04:57:17.708Z"
                    }
                ]
            }
        },
        {
            "id": 72,
            "uuid": "60fc0cec-496b-457e-94bd-2576dd09a80b",
            "shortUUID": "cYBuEBvRvJgJRR4xrA92mx",
            "url": "https://dob.media.fibodo.com/videos/watch/60fc0cec-496b-457e-94bd-2576dd09a80b",
            "name": "216 - Hatha Yoga - Charles - 29min - HD",
            "category": {
                "id": 5,
                "label": "Sports"
            },
            "licence": {
                "id": 4,
                "label": "Attribution - Non Commercial"
            },
            "language": {
                "id": "en",
                "label": "English"
            },
            "privacy": {
                "id": 1,
                "label": "Public"
            },
            "nsfw": false,
            "truncatedDescription": null,
            "description": null,
            "isLocal": true,
            "duration": 1733,
            "aspectRatio": 1.7778,
            "views": 15,
            "viewers": 0,
            "likes": 0,
            "dislikes": 0,
            "thumbnailPath": "/lazy-static/thumbnails/a0f72422-a970-479b-b248-dd54a13d803d.jpg",
            "previewPath": "/lazy-static/previews/b5e6cbda-5e21-490d-94ed-21ce50e7de0c.jpg",
            "embedPath": "/videos/embed/60fc0cec-496b-457e-94bd-2576dd09a80b",
            "createdAt": "2024-05-28T08:21:26.369Z",
            "updatedAt": "2025-05-22T09:34:23.051Z",
            "publishedAt": "2024-06-01T21:52:15.466Z",
            "originallyPublishedAt": null,
            "isLive": false,
            "account": {
                "id": 7,
                "displayName": "DoingOurBit",
                "name": "d_o_b",
                "url": "https://dob.media.fibodo.com/accounts/d_o_b",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/49f68f0b-810a-407a-ba01-99f7b1b35a05.jpg",
                        "createdAt": "2024-05-27T04:53:46.173Z",
                        "updatedAt": "2024-05-27T04:53:46.173Z"
                    },
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/39a47004-7915-46ee-bc6d-d3638300f51d.jpg",
                        "createdAt": "2024-05-27T04:53:46.090Z",
                        "updatedAt": "2024-05-27T04:53:46.090Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/4087d3ca-9e7c-46e5-a3ea-ca78e14affca.jpg",
                        "createdAt": "2024-05-27T04:53:46.167Z",
                        "updatedAt": "2024-05-27T04:53:46.167Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/6be0086f-1036-4546-8daf-e31c255a19f2.jpg",
                        "createdAt": "2024-05-27T04:53:46.170Z",
                        "updatedAt": "2024-05-27T04:53:46.170Z"
                    }
                ]
            },
            "channel": {
                "id": 12,
                "name": "johnson_digital2",
                "displayName": "Johnson Digital Video’s",
                "url": "https://dob.media.fibodo.com/video-channels/johnson_digital2",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/9d9ae58a-5eff-42b7-8364-7e2cc10f45a1.png",
                        "createdAt": "2024-05-27T04:57:17.704Z",
                        "updatedAt": "2024-05-27T04:57:17.704Z"
                    },
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/378ceeb5-7346-4dbb-b433-509ef44a4581.png",
                        "createdAt": "2024-05-27T04:57:17.681Z",
                        "updatedAt": "2024-05-27T04:57:17.681Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/f6ac3eee-140c-4ee8-97d3-bc6a1b246dde.png",
                        "createdAt": "2024-05-27T04:57:17.701Z",
                        "updatedAt": "2024-05-27T04:57:17.701Z"
                    },
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/45c4827a-2dfc-41d3-83ff-de23948620b9.png",
                        "createdAt": "2024-05-27T04:57:17.708Z",
                        "updatedAt": "2024-05-27T04:57:17.708Z"
                    }
                ]
            }
        },
        {
            "id": 73,
            "uuid": "0bbe90a8-ec85-47c0-9229-f8fd72b901a4",
            "shortUUID": "2s7CM3Ms9E8K3vPiQGh5pw",
            "url": "https://dob.media.fibodo.com/videos/watch/0bbe90a8-ec85-47c0-9229-f8fd72b901a4",
            "name": "240 - Cardio Dance - Faye - 23min",
            "category": {
                "id": 5,
                "label": "Sports"
            },
            "licence": {
                "id": 4,
                "label": "Attribution - Non Commercial"
            },
            "language": {
                "id": "en",
                "label": "English"
            },
            "privacy": {
                "id": 1,
                "label": "Public"
            },
            "nsfw": false,
            "truncatedDescription": null,
            "description": null,
            "isLocal": true,
            "duration": 1406,
            "aspectRatio": 1.7778,
            "views": 20,
            "viewers": 0,
            "likes": 0,
            "dislikes": 0,
            "thumbnailPath": "/lazy-static/thumbnails/c3b196ee-5888-41af-9504-e791b3545a49.jpg",
            "previewPath": "/lazy-static/previews/a3b18e82-02cf-4545-b6b9-e45682ab7a37.jpg",
            "embedPath": "/videos/embed/0bbe90a8-ec85-47c0-9229-f8fd72b901a4",
            "createdAt": "2024-05-28T09:00:44.464Z",
            "updatedAt": "2025-02-10T17:47:47.551Z",
            "publishedAt": "2024-06-01T21:51:01.790Z",
            "originallyPublishedAt": null,
            "isLive": false,
            "account": {
                "id": 7,
                "displayName": "DoingOurBit",
                "name": "d_o_b",
                "url": "https://dob.media.fibodo.com/accounts/d_o_b",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/6be0086f-1036-4546-8daf-e31c255a19f2.jpg",
                        "createdAt": "2024-05-27T04:53:46.170Z",
                        "updatedAt": "2024-05-27T04:53:46.170Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/4087d3ca-9e7c-46e5-a3ea-ca78e14affca.jpg",
                        "createdAt": "2024-05-27T04:53:46.167Z",
                        "updatedAt": "2024-05-27T04:53:46.167Z"
                    },
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/39a47004-7915-46ee-bc6d-d3638300f51d.jpg",
                        "createdAt": "2024-05-27T04:53:46.090Z",
                        "updatedAt": "2024-05-27T04:53:46.090Z"
                    },
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/49f68f0b-810a-407a-ba01-99f7b1b35a05.jpg",
                        "createdAt": "2024-05-27T04:53:46.173Z",
                        "updatedAt": "2024-05-27T04:53:46.173Z"
                    }
                ]
            },
            "channel": {
                "id": 12,
                "name": "johnson_digital2",
                "displayName": "Johnson Digital Video’s",
                "url": "https://dob.media.fibodo.com/video-channels/johnson_digital2",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/45c4827a-2dfc-41d3-83ff-de23948620b9.png",
                        "createdAt": "2024-05-27T04:57:17.708Z",
                        "updatedAt": "2024-05-27T04:57:17.708Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/9d9ae58a-5eff-42b7-8364-7e2cc10f45a1.png",
                        "createdAt": "2024-05-27T04:57:17.704Z",
                        "updatedAt": "2024-05-27T04:57:17.704Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/f6ac3eee-140c-4ee8-97d3-bc6a1b246dde.png",
                        "createdAt": "2024-05-27T04:57:17.701Z",
                        "updatedAt": "2024-05-27T04:57:17.701Z"
                    },
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/378ceeb5-7346-4dbb-b433-509ef44a4581.png",
                        "createdAt": "2024-05-27T04:57:17.681Z",
                        "updatedAt": "2024-05-27T04:57:17.681Z"
                    }
                ]
            }
        },
        {
            "id": 75,
            "uuid": "e8e9d617-2731-4566-bd99-824b0b97f862",
            "shortUUID": "uL9u2kFq6AxsJL11dwsXaL",
            "url": "https://dob.media.fibodo.com/videos/watch/e8e9d617-2731-4566-bd99-824b0b97f862",
            "name": "241 - Cardio Dance - Faye - 24min",
            "category": {
                "id": 5,
                "label": "Sports"
            },
            "licence": {
                "id": 4,
                "label": "Attribution - Non Commercial"
            },
            "language": {
                "id": "en",
                "label": "English"
            },
            "privacy": {
                "id": 1,
                "label": "Public"
            },
            "nsfw": false,
            "truncatedDescription": null,
            "description": null,
            "isLocal": true,
            "duration": 1356,
            "aspectRatio": 1.7778,
            "views": 11,
            "viewers": 0,
            "likes": 0,
            "dislikes": 0,
            "thumbnailPath": "/lazy-static/thumbnails/744368d5-3b38-4abd-bb22-ec66732ee941.jpg",
            "previewPath": "/lazy-static/previews/45c96e33-19a2-4a16-8532-f27463d692ff.jpg",
            "embedPath": "/videos/embed/e8e9d617-2731-4566-bd99-824b0b97f862",
            "createdAt": "2024-05-28T10:58:17.571Z",
            "updatedAt": "2025-06-06T23:04:23.497Z",
            "publishedAt": "2024-06-01T21:49:24.912Z",
            "originallyPublishedAt": null,
            "isLive": false,
            "account": {
                "id": 7,
                "displayName": "DoingOurBit",
                "name": "d_o_b",
                "url": "https://dob.media.fibodo.com/accounts/d_o_b",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/49f68f0b-810a-407a-ba01-99f7b1b35a05.jpg",
                        "createdAt": "2024-05-27T04:53:46.173Z",
                        "updatedAt": "2024-05-27T04:53:46.173Z"
                    },
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/39a47004-7915-46ee-bc6d-d3638300f51d.jpg",
                        "createdAt": "2024-05-27T04:53:46.090Z",
                        "updatedAt": "2024-05-27T04:53:46.090Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/4087d3ca-9e7c-46e5-a3ea-ca78e14affca.jpg",
                        "createdAt": "2024-05-27T04:53:46.167Z",
                        "updatedAt": "2024-05-27T04:53:46.167Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/6be0086f-1036-4546-8daf-e31c255a19f2.jpg",
                        "createdAt": "2024-05-27T04:53:46.170Z",
                        "updatedAt": "2024-05-27T04:53:46.170Z"
                    }
                ]
            },
            "channel": {
                "id": 12,
                "name": "johnson_digital2",
                "displayName": "Johnson Digital Video’s",
                "url": "https://dob.media.fibodo.com/video-channels/johnson_digital2",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/9d9ae58a-5eff-42b7-8364-7e2cc10f45a1.png",
                        "createdAt": "2024-05-27T04:57:17.704Z",
                        "updatedAt": "2024-05-27T04:57:17.704Z"
                    },
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/45c4827a-2dfc-41d3-83ff-de23948620b9.png",
                        "createdAt": "2024-05-27T04:57:17.708Z",
                        "updatedAt": "2024-05-27T04:57:17.708Z"
                    },
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/378ceeb5-7346-4dbb-b433-509ef44a4581.png",
                        "createdAt": "2024-05-27T04:57:17.681Z",
                        "updatedAt": "2024-05-27T04:57:17.681Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/f6ac3eee-140c-4ee8-97d3-bc6a1b246dde.png",
                        "createdAt": "2024-05-27T04:57:17.701Z",
                        "updatedAt": "2024-05-27T04:57:17.701Z"
                    }
                ]
            }
        },
        {
            "id": 78,
            "uuid": "1b889a42-85d4-495f-b743-4e157adb768e",
            "shortUUID": "4pcuju4uEiYzzxh4h73S5N",
            "url": "https://dob.media.fibodo.com/videos/watch/1b889a42-85d4-495f-b743-4e157adb768e",
            "name": "248 - Core X - Maddie - 27mins",
            "category": {
                "id": 5,
                "label": "Sports"
            },
            "licence": {
                "id": 4,
                "label": "Attribution - Non Commercial"
            },
            "language": {
                "id": "en",
                "label": "English"
            },
            "privacy": {
                "id": 1,
                "label": "Public"
            },
            "nsfw": false,
            "truncatedDescription": null,
            "description": null,
            "isLocal": true,
            "duration": 1627,
            "aspectRatio": 1.7778,
            "views": 8,
            "viewers": 0,
            "likes": 0,
            "dislikes": 0,
            "thumbnailPath": "/lazy-static/thumbnails/b91b28b6-cd0f-4190-ada6-fce21213b9fb.jpg",
            "previewPath": "/lazy-static/previews/057c4a1b-0ba8-40c8-8f86-f1e558af43d9.jpg",
            "embedPath": "/videos/embed/1b889a42-85d4-495f-b743-4e157adb768e",
            "createdAt": "2024-05-28T12:41:31.876Z",
            "updatedAt": "2025-05-29T10:04:23.253Z",
            "publishedAt": "2024-06-01T21:47:26.668Z",
            "originallyPublishedAt": null,
            "isLive": false,
            "account": {
                "id": 7,
                "displayName": "DoingOurBit",
                "name": "d_o_b",
                "url": "https://dob.media.fibodo.com/accounts/d_o_b",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/49f68f0b-810a-407a-ba01-99f7b1b35a05.jpg",
                        "createdAt": "2024-05-27T04:53:46.173Z",
                        "updatedAt": "2024-05-27T04:53:46.173Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/6be0086f-1036-4546-8daf-e31c255a19f2.jpg",
                        "createdAt": "2024-05-27T04:53:46.170Z",
                        "updatedAt": "2024-05-27T04:53:46.170Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/4087d3ca-9e7c-46e5-a3ea-ca78e14affca.jpg",
                        "createdAt": "2024-05-27T04:53:46.167Z",
                        "updatedAt": "2024-05-27T04:53:46.167Z"
                    },
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/39a47004-7915-46ee-bc6d-d3638300f51d.jpg",
                        "createdAt": "2024-05-27T04:53:46.090Z",
                        "updatedAt": "2024-05-27T04:53:46.090Z"
                    }
                ]
            },
            "channel": {
                "id": 12,
                "name": "johnson_digital2",
                "displayName": "Johnson Digital Video’s",
                "url": "https://dob.media.fibodo.com/video-channels/johnson_digital2",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/45c4827a-2dfc-41d3-83ff-de23948620b9.png",
                        "createdAt": "2024-05-27T04:57:17.708Z",
                        "updatedAt": "2024-05-27T04:57:17.708Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/9d9ae58a-5eff-42b7-8364-7e2cc10f45a1.png",
                        "createdAt": "2024-05-27T04:57:17.704Z",
                        "updatedAt": "2024-05-27T04:57:17.704Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/f6ac3eee-140c-4ee8-97d3-bc6a1b246dde.png",
                        "createdAt": "2024-05-27T04:57:17.701Z",
                        "updatedAt": "2024-05-27T04:57:17.701Z"
                    },
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/378ceeb5-7346-4dbb-b433-509ef44a4581.png",
                        "createdAt": "2024-05-27T04:57:17.681Z",
                        "updatedAt": "2024-05-27T04:57:17.681Z"
                    }
                ]
            }
        },
        {
            "id": 79,
            "uuid": "b6187a0c-3e22-41df-b434-5c6a744e1605",
            "shortUUID": "oubHdBo7dMVERuHAsBjxg4",
            "url": "https://dob.media.fibodo.com/videos/watch/b6187a0c-3e22-41df-b434-5c6a744e1605",
            "name": "280 - Sculpt - Emily - 29mins",
            "category": {
                "id": 5,
                "label": "Sports"
            },
            "licence": {
                "id": 4,
                "label": "Attribution - Non Commercial"
            },
            "language": {
                "id": "en",
                "label": "English"
            },
            "privacy": {
                "id": 1,
                "label": "Public"
            },
            "nsfw": false,
            "truncatedDescription": null,
            "description": null,
            "isLocal": true,
            "duration": 1699,
            "aspectRatio": 1.7778,
            "views": 6,
            "viewers": 0,
            "likes": 0,
            "dislikes": 0,
            "thumbnailPath": "/lazy-static/thumbnails/2ee6d6f3-80e8-4b6b-86d2-5c4dcf4812ee.jpg",
            "previewPath": "/lazy-static/previews/2a090432-14bb-41ae-8a54-293425088418.jpg",
            "embedPath": "/videos/embed/b6187a0c-3e22-41df-b434-5c6a744e1605",
            "createdAt": "2024-05-28T13:37:26.571Z",
            "updatedAt": "2025-01-13T11:47:39.364Z",
            "publishedAt": "2024-05-31T09:36:02.148Z",
            "originallyPublishedAt": null,
            "isLive": false,
            "account": {
                "id": 7,
                "displayName": "DoingOurBit",
                "name": "d_o_b",
                "url": "https://dob.media.fibodo.com/accounts/d_o_b",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/49f68f0b-810a-407a-ba01-99f7b1b35a05.jpg",
                        "createdAt": "2024-05-27T04:53:46.173Z",
                        "updatedAt": "2024-05-27T04:53:46.173Z"
                    },
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/39a47004-7915-46ee-bc6d-d3638300f51d.jpg",
                        "createdAt": "2024-05-27T04:53:46.090Z",
                        "updatedAt": "2024-05-27T04:53:46.090Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/4087d3ca-9e7c-46e5-a3ea-ca78e14affca.jpg",
                        "createdAt": "2024-05-27T04:53:46.167Z",
                        "updatedAt": "2024-05-27T04:53:46.167Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/6be0086f-1036-4546-8daf-e31c255a19f2.jpg",
                        "createdAt": "2024-05-27T04:53:46.170Z",
                        "updatedAt": "2024-05-27T04:53:46.170Z"
                    }
                ]
            },
            "channel": {
                "id": 12,
                "name": "johnson_digital2",
                "displayName": "Johnson Digital Video’s",
                "url": "https://dob.media.fibodo.com/video-channels/johnson_digital2",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/45c4827a-2dfc-41d3-83ff-de23948620b9.png",
                        "createdAt": "2024-05-27T04:57:17.708Z",
                        "updatedAt": "2024-05-27T04:57:17.708Z"
                    },
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/378ceeb5-7346-4dbb-b433-509ef44a4581.png",
                        "createdAt": "2024-05-27T04:57:17.681Z",
                        "updatedAt": "2024-05-27T04:57:17.681Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/f6ac3eee-140c-4ee8-97d3-bc6a1b246dde.png",
                        "createdAt": "2024-05-27T04:57:17.701Z",
                        "updatedAt": "2024-05-27T04:57:17.701Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/9d9ae58a-5eff-42b7-8364-7e2cc10f45a1.png",
                        "createdAt": "2024-05-27T04:57:17.704Z",
                        "updatedAt": "2024-05-27T04:57:17.704Z"
                    }
                ]
            }
        },
        {
            "id": 81,
            "uuid": "7a9a82e2-c1d9-49fb-81c1-f3b28adac515",
            "shortUUID": "g96DCDJFniNEnjy8fyxVGZ",
            "url": "https://dob.media.fibodo.com/videos/watch/7a9a82e2-c1d9-49fb-81c1-f3b28adac515",
            "name": "281 - Sculpt - Emily - 27mins",
            "category": {
                "id": 5,
                "label": "Sports"
            },
            "licence": {
                "id": 4,
                "label": "Attribution - Non Commercial"
            },
            "language": {
                "id": "en",
                "label": "English"
            },
            "privacy": {
                "id": 1,
                "label": "Public"
            },
            "nsfw": false,
            "truncatedDescription": null,
            "description": null,
            "isLocal": true,
            "duration": 1651,
            "aspectRatio": 1.7778,
            "views": 3,
            "viewers": 0,
            "likes": 0,
            "dislikes": 0,
            "thumbnailPath": "/lazy-static/thumbnails/68453109-6e7d-4f5f-8997-15f941b2a746.jpg",
            "previewPath": "/lazy-static/previews/d7a00b76-029e-4170-b589-d82c0e4931b1.jpg",
            "embedPath": "/videos/embed/7a9a82e2-c1d9-49fb-81c1-f3b28adac515",
            "createdAt": "2024-05-28T16:05:15.274Z",
            "updatedAt": "2025-01-01T20:17:38.957Z",
            "publishedAt": "2024-05-31T04:18:26.475Z",
            "originallyPublishedAt": null,
            "isLive": false,
            "account": {
                "id": 7,
                "displayName": "DoingOurBit",
                "name": "d_o_b",
                "url": "https://dob.media.fibodo.com/accounts/d_o_b",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/49f68f0b-810a-407a-ba01-99f7b1b35a05.jpg",
                        "createdAt": "2024-05-27T04:53:46.173Z",
                        "updatedAt": "2024-05-27T04:53:46.173Z"
                    },
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/39a47004-7915-46ee-bc6d-d3638300f51d.jpg",
                        "createdAt": "2024-05-27T04:53:46.090Z",
                        "updatedAt": "2024-05-27T04:53:46.090Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/4087d3ca-9e7c-46e5-a3ea-ca78e14affca.jpg",
                        "createdAt": "2024-05-27T04:53:46.167Z",
                        "updatedAt": "2024-05-27T04:53:46.167Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/6be0086f-1036-4546-8daf-e31c255a19f2.jpg",
                        "createdAt": "2024-05-27T04:53:46.170Z",
                        "updatedAt": "2024-05-27T04:53:46.170Z"
                    }
                ]
            },
            "channel": {
                "id": 12,
                "name": "johnson_digital2",
                "displayName": "Johnson Digital Video’s",
                "url": "https://dob.media.fibodo.com/video-channels/johnson_digital2",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/45c4827a-2dfc-41d3-83ff-de23948620b9.png",
                        "createdAt": "2024-05-27T04:57:17.708Z",
                        "updatedAt": "2024-05-27T04:57:17.708Z"
                    },
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/378ceeb5-7346-4dbb-b433-509ef44a4581.png",
                        "createdAt": "2024-05-27T04:57:17.681Z",
                        "updatedAt": "2024-05-27T04:57:17.681Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/f6ac3eee-140c-4ee8-97d3-bc6a1b246dde.png",
                        "createdAt": "2024-05-27T04:57:17.701Z",
                        "updatedAt": "2024-05-27T04:57:17.701Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/9d9ae58a-5eff-42b7-8364-7e2cc10f45a1.png",
                        "createdAt": "2024-05-27T04:57:17.704Z",
                        "updatedAt": "2024-05-27T04:57:17.704Z"
                    }
                ]
            }
        },
        {
            "id": 83,
            "uuid": "57e78f98-a9d7-4fca-9919-0051cbde631b",
            "shortUUID": "bRzCXaVRTK97DBb9cTMzCX",
            "url": "https://dob.media.fibodo.com/videos/watch/57e78f98-a9d7-4fca-9919-0051cbde631b",
            "name": "217 - Vinyasa Yoga - Charles - 29min - HD",
            "category": {
                "id": 5,
                "label": "Sports"
            },
            "licence": {
                "id": 4,
                "label": "Attribution - Non Commercial"
            },
            "language": {
                "id": "en",
                "label": "English"
            },
            "privacy": {
                "id": 1,
                "label": "Public"
            },
            "nsfw": false,
            "truncatedDescription": null,
            "description": null,
            "isLocal": true,
            "duration": 1726,
            "aspectRatio": 1.7778,
            "views": 5,
            "viewers": 0,
            "likes": 0,
            "dislikes": 0,
            "thumbnailPath": "/lazy-static/thumbnails/a4ed8a53-3027-4b54-9cee-72350156eb8e.jpg",
            "previewPath": "/lazy-static/previews/cdb0dbbc-50aa-4063-9363-b82123ca39b1.jpg",
            "embedPath": "/videos/embed/57e78f98-a9d7-4fca-9919-0051cbde631b",
            "createdAt": "2024-05-28T17:01:19.070Z",
            "updatedAt": "2025-02-08T14:17:47.517Z",
            "publishedAt": "2024-05-31T02:42:30.363Z",
            "originallyPublishedAt": null,
            "isLive": false,
            "account": {
                "id": 7,
                "displayName": "DoingOurBit",
                "name": "d_o_b",
                "url": "https://dob.media.fibodo.com/accounts/d_o_b",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/49f68f0b-810a-407a-ba01-99f7b1b35a05.jpg",
                        "createdAt": "2024-05-27T04:53:46.173Z",
                        "updatedAt": "2024-05-27T04:53:46.173Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/6be0086f-1036-4546-8daf-e31c255a19f2.jpg",
                        "createdAt": "2024-05-27T04:53:46.170Z",
                        "updatedAt": "2024-05-27T04:53:46.170Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/4087d3ca-9e7c-46e5-a3ea-ca78e14affca.jpg",
                        "createdAt": "2024-05-27T04:53:46.167Z",
                        "updatedAt": "2024-05-27T04:53:46.167Z"
                    },
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/39a47004-7915-46ee-bc6d-d3638300f51d.jpg",
                        "createdAt": "2024-05-27T04:53:46.090Z",
                        "updatedAt": "2024-05-27T04:53:46.090Z"
                    }
                ]
            },
            "channel": {
                "id": 12,
                "name": "johnson_digital2",
                "displayName": "Johnson Digital Video’s",
                "url": "https://dob.media.fibodo.com/video-channels/johnson_digital2",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/f6ac3eee-140c-4ee8-97d3-bc6a1b246dde.png",
                        "createdAt": "2024-05-27T04:57:17.701Z",
                        "updatedAt": "2024-05-27T04:57:17.701Z"
                    },
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/45c4827a-2dfc-41d3-83ff-de23948620b9.png",
                        "createdAt": "2024-05-27T04:57:17.708Z",
                        "updatedAt": "2024-05-27T04:57:17.708Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/9d9ae58a-5eff-42b7-8364-7e2cc10f45a1.png",
                        "createdAt": "2024-05-27T04:57:17.704Z",
                        "updatedAt": "2024-05-27T04:57:17.704Z"
                    },
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/378ceeb5-7346-4dbb-b433-509ef44a4581.png",
                        "createdAt": "2024-05-27T04:57:17.681Z",
                        "updatedAt": "2024-05-27T04:57:17.681Z"
                    }
                ]
            }
        },
        {
            "id": 77,
            "uuid": "afca4307-683d-4e71-a3a7-a2748ad93083",
            "shortUUID": "nH2ovjHz8fBd7HdUD5vBJK",
            "url": "https://dob.media.fibodo.com/videos/watch/afca4307-683d-4e71-a3a7-a2748ad93083",
            "name": "247 - Core X - Emily - 29mins",
            "category": {
                "id": 5,
                "label": "Sports"
            },
            "licence": {
                "id": 4,
                "label": "Attribution - Non Commercial"
            },
            "language": {
                "id": "en",
                "label": "English"
            },
            "privacy": {
                "id": 1,
                "label": "Public"
            },
            "nsfw": false,
            "truncatedDescription": null,
            "description": null,
            "isLocal": true,
            "duration": 1707,
            "aspectRatio": 1.7778,
            "views": 3,
            "viewers": 0,
            "likes": 0,
            "dislikes": 0,
            "thumbnailPath": "/lazy-static/thumbnails/4c3d1d0c-6eb5-4bea-aef7-9e6258b9567a.jpg",
            "previewPath": "/lazy-static/previews/31a065bb-6e8b-433a-9ddd-5d4e3028a2f7.jpg",
            "embedPath": "/videos/embed/afca4307-683d-4e71-a3a7-a2748ad93083",
            "createdAt": "2024-05-28T11:54:52.266Z",
            "updatedAt": "2025-05-29T09:34:23.350Z",
            "publishedAt": "2024-05-30T16:23:07.788Z",
            "originallyPublishedAt": null,
            "isLive": false,
            "account": {
                "id": 7,
                "displayName": "DoingOurBit",
                "name": "d_o_b",
                "url": "https://dob.media.fibodo.com/accounts/d_o_b",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/39a47004-7915-46ee-bc6d-d3638300f51d.jpg",
                        "createdAt": "2024-05-27T04:53:46.090Z",
                        "updatedAt": "2024-05-27T04:53:46.090Z"
                    },
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/49f68f0b-810a-407a-ba01-99f7b1b35a05.jpg",
                        "createdAt": "2024-05-27T04:53:46.173Z",
                        "updatedAt": "2024-05-27T04:53:46.173Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/6be0086f-1036-4546-8daf-e31c255a19f2.jpg",
                        "createdAt": "2024-05-27T04:53:46.170Z",
                        "updatedAt": "2024-05-27T04:53:46.170Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/4087d3ca-9e7c-46e5-a3ea-ca78e14affca.jpg",
                        "createdAt": "2024-05-27T04:53:46.167Z",
                        "updatedAt": "2024-05-27T04:53:46.167Z"
                    }
                ]
            },
            "channel": {
                "id": 12,
                "name": "johnson_digital2",
                "displayName": "Johnson Digital Video’s",
                "url": "https://dob.media.fibodo.com/video-channels/johnson_digital2",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/378ceeb5-7346-4dbb-b433-509ef44a4581.png",
                        "createdAt": "2024-05-27T04:57:17.681Z",
                        "updatedAt": "2024-05-27T04:57:17.681Z"
                    },
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/45c4827a-2dfc-41d3-83ff-de23948620b9.png",
                        "createdAt": "2024-05-27T04:57:17.708Z",
                        "updatedAt": "2024-05-27T04:57:17.708Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/9d9ae58a-5eff-42b7-8364-7e2cc10f45a1.png",
                        "createdAt": "2024-05-27T04:57:17.704Z",
                        "updatedAt": "2024-05-27T04:57:17.704Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/f6ac3eee-140c-4ee8-97d3-bc6a1b246dde.png",
                        "createdAt": "2024-05-27T04:57:17.701Z",
                        "updatedAt": "2024-05-27T04:57:17.701Z"
                    }
                ]
            }
        },
        {
            "id": 65,
            "uuid": "2db1e42a-5aaf-4643-8415-f3e4f576b21e",
            "shortUUID": "6DgGTfbjgmbCT2Qr5CY925",
            "url": "https://dob.media.fibodo.com/videos/watch/2db1e42a-5aaf-4643-8415-f3e4f576b21e",
            "name": "232 - Fifteen - Shinead - 27mins",
            "category": {
                "id": 5,
                "label": "Sports"
            },
            "licence": {
                "id": 4,
                "label": "Attribution - Non Commercial"
            },
            "language": {
                "id": "en",
                "label": "English"
            },
            "privacy": {
                "id": 1,
                "label": "Public"
            },
            "nsfw": false,
            "truncatedDescription": null,
            "description": null,
            "isLocal": true,
            "duration": 1627,
            "aspectRatio": 1.7778,
            "views": 3,
            "viewers": 0,
            "likes": 0,
            "dislikes": 0,
            "thumbnailPath": "/lazy-static/thumbnails/77a7dd3a-5ea7-485d-88c4-4582d5bd9ade.jpg",
            "previewPath": "/lazy-static/previews/22e6accb-2784-406c-ab8c-939b43be5b1c.jpg",
            "embedPath": "/videos/embed/2db1e42a-5aaf-4643-8415-f3e4f576b21e",
            "createdAt": "2024-05-27T22:58:54.465Z",
            "updatedAt": "2024-11-18T13:23:49.523Z",
            "publishedAt": "2024-05-30T12:33:36.768Z",
            "originallyPublishedAt": null,
            "isLive": false,
            "account": {
                "id": 7,
                "displayName": "DoingOurBit",
                "name": "d_o_b",
                "url": "https://dob.media.fibodo.com/accounts/d_o_b",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/39a47004-7915-46ee-bc6d-d3638300f51d.jpg",
                        "createdAt": "2024-05-27T04:53:46.090Z",
                        "updatedAt": "2024-05-27T04:53:46.090Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/4087d3ca-9e7c-46e5-a3ea-ca78e14affca.jpg",
                        "createdAt": "2024-05-27T04:53:46.167Z",
                        "updatedAt": "2024-05-27T04:53:46.167Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/6be0086f-1036-4546-8daf-e31c255a19f2.jpg",
                        "createdAt": "2024-05-27T04:53:46.170Z",
                        "updatedAt": "2024-05-27T04:53:46.170Z"
                    },
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/49f68f0b-810a-407a-ba01-99f7b1b35a05.jpg",
                        "createdAt": "2024-05-27T04:53:46.173Z",
                        "updatedAt": "2024-05-27T04:53:46.173Z"
                    }
                ]
            },
            "channel": {
                "id": 12,
                "name": "johnson_digital2",
                "displayName": "Johnson Digital Video’s",
                "url": "https://dob.media.fibodo.com/video-channels/johnson_digital2",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/378ceeb5-7346-4dbb-b433-509ef44a4581.png",
                        "createdAt": "2024-05-27T04:57:17.681Z",
                        "updatedAt": "2024-05-27T04:57:17.681Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/f6ac3eee-140c-4ee8-97d3-bc6a1b246dde.png",
                        "createdAt": "2024-05-27T04:57:17.701Z",
                        "updatedAt": "2024-05-27T04:57:17.701Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/9d9ae58a-5eff-42b7-8364-7e2cc10f45a1.png",
                        "createdAt": "2024-05-27T04:57:17.704Z",
                        "updatedAt": "2024-05-27T04:57:17.704Z"
                    },
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/45c4827a-2dfc-41d3-83ff-de23948620b9.png",
                        "createdAt": "2024-05-27T04:57:17.708Z",
                        "updatedAt": "2024-05-27T04:57:17.708Z"
                    }
                ]
            }
        },
        {
            "id": 66,
            "uuid": "16331a63-1455-4214-aeca-0ef62538f909",
            "shortUUID": "3JZKhvJ1C2SKkyy972VxXx",
            "url": "https://dob.media.fibodo.com/videos/watch/16331a63-1455-4214-aeca-0ef62538f909",
            "name": "225 - Circuit Training - Emily -  Strong - 31mins",
            "category": {
                "id": 5,
                "label": "Sports"
            },
            "licence": {
                "id": 4,
                "label": "Attribution - Non Commercial"
            },
            "language": {
                "id": "en",
                "label": "English"
            },
            "privacy": {
                "id": 1,
                "label": "Public"
            },
            "nsfw": false,
            "truncatedDescription": null,
            "description": null,
            "isLocal": true,
            "duration": 1859,
            "aspectRatio": 1.7778,
            "views": 5,
            "viewers": 0,
            "likes": 0,
            "dislikes": 0,
            "thumbnailPath": "/lazy-static/thumbnails/38805be8-80f7-4914-b846-2c1ba3c26130.jpg",
            "previewPath": "/lazy-static/previews/05a239c6-1e93-4af8-a343-bc5e0cc30750.jpg",
            "embedPath": "/videos/embed/16331a63-1455-4214-aeca-0ef62538f909",
            "createdAt": "2024-05-27T23:06:59.371Z",
            "updatedAt": "2025-06-17T12:34:23.979Z",
            "publishedAt": "2024-05-30T05:25:03.028Z",
            "originallyPublishedAt": null,
            "isLive": false,
            "account": {
                "id": 7,
                "displayName": "DoingOurBit",
                "name": "d_o_b",
                "url": "https://dob.media.fibodo.com/accounts/d_o_b",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/39a47004-7915-46ee-bc6d-d3638300f51d.jpg",
                        "createdAt": "2024-05-27T04:53:46.090Z",
                        "updatedAt": "2024-05-27T04:53:46.090Z"
                    },
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/49f68f0b-810a-407a-ba01-99f7b1b35a05.jpg",
                        "createdAt": "2024-05-27T04:53:46.173Z",
                        "updatedAt": "2024-05-27T04:53:46.173Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/6be0086f-1036-4546-8daf-e31c255a19f2.jpg",
                        "createdAt": "2024-05-27T04:53:46.170Z",
                        "updatedAt": "2024-05-27T04:53:46.170Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/4087d3ca-9e7c-46e5-a3ea-ca78e14affca.jpg",
                        "createdAt": "2024-05-27T04:53:46.167Z",
                        "updatedAt": "2024-05-27T04:53:46.167Z"
                    }
                ]
            },
            "channel": {
                "id": 12,
                "name": "johnson_digital2",
                "displayName": "Johnson Digital Video’s",
                "url": "https://dob.media.fibodo.com/video-channels/johnson_digital2",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/378ceeb5-7346-4dbb-b433-509ef44a4581.png",
                        "createdAt": "2024-05-27T04:57:17.681Z",
                        "updatedAt": "2024-05-27T04:57:17.681Z"
                    },
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/45c4827a-2dfc-41d3-83ff-de23948620b9.png",
                        "createdAt": "2024-05-27T04:57:17.708Z",
                        "updatedAt": "2024-05-27T04:57:17.708Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/9d9ae58a-5eff-42b7-8364-7e2cc10f45a1.png",
                        "createdAt": "2024-05-27T04:57:17.704Z",
                        "updatedAt": "2024-05-27T04:57:17.704Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/f6ac3eee-140c-4ee8-97d3-bc6a1b246dde.png",
                        "createdAt": "2024-05-27T04:57:17.701Z",
                        "updatedAt": "2024-05-27T04:57:17.701Z"
                    }
                ]
            }
        },
        {
            "id": 67,
            "uuid": "2790759b-bd67-448e-8db9-072815876dcf",
            "shortUUID": "5Tn3U4sxu4KZDwWiGEFcce",
            "url": "https://dob.media.fibodo.com/videos/watch/2790759b-bd67-448e-8db9-072815876dcf",
            "name": "197 - Kettlebell - Faye- 25mins",
            "category": {
                "id": 5,
                "label": "Sports"
            },
            "licence": {
                "id": 4,
                "label": "Attribution - Non Commercial"
            },
            "language": {
                "id": "en",
                "label": "English"
            },
            "privacy": {
                "id": 1,
                "label": "Public"
            },
            "nsfw": false,
            "truncatedDescription": null,
            "description": null,
            "isLocal": true,
            "duration": 1534,
            "aspectRatio": 1.7778,
            "views": 10,
            "viewers": 0,
            "likes": 0,
            "dislikes": 0,
            "thumbnailPath": "/lazy-static/thumbnails/6ace3a84-a980-426b-bb04-142fee3ee287.jpg",
            "previewPath": "/lazy-static/previews/18c0ae4c-669b-47ee-8cf5-172c5a009d97.jpg",
            "embedPath": "/videos/embed/2790759b-bd67-448e-8db9-072815876dcf",
            "createdAt": "2024-05-27T23:10:46.574Z",
            "updatedAt": "2025-04-23T10:04:22.436Z",
            "publishedAt": "2024-05-30T05:20:30.819Z",
            "originallyPublishedAt": null,
            "isLive": false,
            "account": {
                "id": 7,
                "displayName": "DoingOurBit",
                "name": "d_o_b",
                "url": "https://dob.media.fibodo.com/accounts/d_o_b",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/49f68f0b-810a-407a-ba01-99f7b1b35a05.jpg",
                        "createdAt": "2024-05-27T04:53:46.173Z",
                        "updatedAt": "2024-05-27T04:53:46.173Z"
                    },
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/39a47004-7915-46ee-bc6d-d3638300f51d.jpg",
                        "createdAt": "2024-05-27T04:53:46.090Z",
                        "updatedAt": "2024-05-27T04:53:46.090Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/4087d3ca-9e7c-46e5-a3ea-ca78e14affca.jpg",
                        "createdAt": "2024-05-27T04:53:46.167Z",
                        "updatedAt": "2024-05-27T04:53:46.167Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/6be0086f-1036-4546-8daf-e31c255a19f2.jpg",
                        "createdAt": "2024-05-27T04:53:46.170Z",
                        "updatedAt": "2024-05-27T04:53:46.170Z"
                    }
                ]
            },
            "channel": {
                "id": 12,
                "name": "johnson_digital2",
                "displayName": "Johnson Digital Video’s",
                "url": "https://dob.media.fibodo.com/video-channels/johnson_digital2",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/45c4827a-2dfc-41d3-83ff-de23948620b9.png",
                        "createdAt": "2024-05-27T04:57:17.708Z",
                        "updatedAt": "2024-05-27T04:57:17.708Z"
                    },
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/378ceeb5-7346-4dbb-b433-509ef44a4581.png",
                        "createdAt": "2024-05-27T04:57:17.681Z",
                        "updatedAt": "2024-05-27T04:57:17.681Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/f6ac3eee-140c-4ee8-97d3-bc6a1b246dde.png",
                        "createdAt": "2024-05-27T04:57:17.701Z",
                        "updatedAt": "2024-05-27T04:57:17.701Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/9d9ae58a-5eff-42b7-8364-7e2cc10f45a1.png",
                        "createdAt": "2024-05-27T04:57:17.704Z",
                        "updatedAt": "2024-05-27T04:57:17.704Z"
                    }
                ]
            }
        },
        {
            "id": 69,
            "uuid": "ca398c2a-c6a4-4e46-82b0-721ac03242da",
            "shortUUID": "qYmoTa6GAoXubd4bPfWzzd",
            "url": "https://dob.media.fibodo.com/videos/watch/ca398c2a-c6a4-4e46-82b0-721ac03242da",
            "name": "213 - Stretch - Emily - 21min - HD",
            "category": {
                "id": 5,
                "label": "Sports"
            },
            "licence": {
                "id": 4,
                "label": "Attribution - Non Commercial"
            },
            "language": {
                "id": "en",
                "label": "English"
            },
            "privacy": {
                "id": 1,
                "label": "Public"
            },
            "nsfw": false,
            "truncatedDescription": null,
            "description": null,
            "isLocal": true,
            "duration": 1260,
            "aspectRatio": 1.7778,
            "views": 10,
            "viewers": 0,
            "likes": 0,
            "dislikes": 0,
            "thumbnailPath": "/lazy-static/thumbnails/0a10e6c7-25e4-47d8-a93d-b46edbc2d2b1.jpg",
            "previewPath": "/lazy-static/previews/f89ecbd3-79a2-4a00-89fe-9a2e62247139.jpg",
            "embedPath": "/videos/embed/ca398c2a-c6a4-4e46-82b0-721ac03242da",
            "createdAt": "2024-05-28T00:28:07.766Z",
            "updatedAt": "2025-02-10T18:47:47.548Z",
            "publishedAt": "2024-05-30T05:19:55.571Z",
            "originallyPublishedAt": null,
            "isLive": false,
            "account": {
                "id": 7,
                "displayName": "DoingOurBit",
                "name": "d_o_b",
                "url": "https://dob.media.fibodo.com/accounts/d_o_b",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/49f68f0b-810a-407a-ba01-99f7b1b35a05.jpg",
                        "createdAt": "2024-05-27T04:53:46.173Z",
                        "updatedAt": "2024-05-27T04:53:46.173Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/6be0086f-1036-4546-8daf-e31c255a19f2.jpg",
                        "createdAt": "2024-05-27T04:53:46.170Z",
                        "updatedAt": "2024-05-27T04:53:46.170Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/4087d3ca-9e7c-46e5-a3ea-ca78e14affca.jpg",
                        "createdAt": "2024-05-27T04:53:46.167Z",
                        "updatedAt": "2024-05-27T04:53:46.167Z"
                    },
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/39a47004-7915-46ee-bc6d-d3638300f51d.jpg",
                        "createdAt": "2024-05-27T04:53:46.090Z",
                        "updatedAt": "2024-05-27T04:53:46.090Z"
                    }
                ]
            },
            "channel": {
                "id": 12,
                "name": "johnson_digital2",
                "displayName": "Johnson Digital Video’s",
                "url": "https://dob.media.fibodo.com/video-channels/johnson_digital2",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/45c4827a-2dfc-41d3-83ff-de23948620b9.png",
                        "createdAt": "2024-05-27T04:57:17.708Z",
                        "updatedAt": "2024-05-27T04:57:17.708Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/9d9ae58a-5eff-42b7-8364-7e2cc10f45a1.png",
                        "createdAt": "2024-05-27T04:57:17.704Z",
                        "updatedAt": "2024-05-27T04:57:17.704Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/f6ac3eee-140c-4ee8-97d3-bc6a1b246dde.png",
                        "createdAt": "2024-05-27T04:57:17.701Z",
                        "updatedAt": "2024-05-27T04:57:17.701Z"
                    },
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/378ceeb5-7346-4dbb-b433-509ef44a4581.png",
                        "createdAt": "2024-05-27T04:57:17.681Z",
                        "updatedAt": "2024-05-27T04:57:17.681Z"
                    }
                ]
            }
        },
        {
            "id": 60,
            "uuid": "2b5e7798-8381-482c-b05e-cbf27f891a55",
            "shortUUID": "6mBwXDcpKbiWCUsNXZ8J4X",
            "url": "https://dob.media.fibodo.com/videos/watch/2b5e7798-8381-482c-b05e-cbf27f891a55",
            "name": "198 - Kettlebell - Faye- 25mins",
            "category": {
                "id": 5,
                "label": "Sports"
            },
            "licence": {
                "id": 4,
                "label": "Attribution - Non Commercial"
            },
            "language": {
                "id": "en",
                "label": "English"
            },
            "privacy": {
                "id": 1,
                "label": "Public"
            },
            "nsfw": false,
            "truncatedDescription": null,
            "description": null,
            "isLocal": true,
            "duration": 1507,
            "aspectRatio": 1.7778,
            "views": 6,
            "viewers": 0,
            "likes": 0,
            "dislikes": 0,
            "thumbnailPath": "/lazy-static/thumbnails/ff8a3d04-9ddb-42e7-8b1b-30c3205706c8.jpg",
            "previewPath": "/lazy-static/previews/600a6d30-20d4-46bc-901a-ef245538330d.jpg",
            "embedPath": "/videos/embed/2b5e7798-8381-482c-b05e-cbf27f891a55",
            "createdAt": "2024-05-27T15:45:40.476Z",
            "updatedAt": "2025-04-23T10:04:22.191Z",
            "publishedAt": "2024-05-30T05:19:18.285Z",
            "originallyPublishedAt": null,
            "isLive": false,
            "account": {
                "id": 7,
                "displayName": "DoingOurBit",
                "name": "d_o_b",
                "url": "https://dob.media.fibodo.com/accounts/d_o_b",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/39a47004-7915-46ee-bc6d-d3638300f51d.jpg",
                        "createdAt": "2024-05-27T04:53:46.090Z",
                        "updatedAt": "2024-05-27T04:53:46.090Z"
                    },
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/49f68f0b-810a-407a-ba01-99f7b1b35a05.jpg",
                        "createdAt": "2024-05-27T04:53:46.173Z",
                        "updatedAt": "2024-05-27T04:53:46.173Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/6be0086f-1036-4546-8daf-e31c255a19f2.jpg",
                        "createdAt": "2024-05-27T04:53:46.170Z",
                        "updatedAt": "2024-05-27T04:53:46.170Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/4087d3ca-9e7c-46e5-a3ea-ca78e14affca.jpg",
                        "createdAt": "2024-05-27T04:53:46.167Z",
                        "updatedAt": "2024-05-27T04:53:46.167Z"
                    }
                ]
            },
            "channel": {
                "id": 12,
                "name": "johnson_digital2",
                "displayName": "Johnson Digital Video’s",
                "url": "https://dob.media.fibodo.com/video-channels/johnson_digital2",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/f6ac3eee-140c-4ee8-97d3-bc6a1b246dde.png",
                        "createdAt": "2024-05-27T04:57:17.701Z",
                        "updatedAt": "2024-05-27T04:57:17.701Z"
                    },
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/45c4827a-2dfc-41d3-83ff-de23948620b9.png",
                        "createdAt": "2024-05-27T04:57:17.708Z",
                        "updatedAt": "2024-05-27T04:57:17.708Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/9d9ae58a-5eff-42b7-8364-7e2cc10f45a1.png",
                        "createdAt": "2024-05-27T04:57:17.704Z",
                        "updatedAt": "2024-05-27T04:57:17.704Z"
                    },
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/378ceeb5-7346-4dbb-b433-509ef44a4581.png",
                        "createdAt": "2024-05-27T04:57:17.681Z",
                        "updatedAt": "2024-05-27T04:57:17.681Z"
                    }
                ]
            }
        },
        {
            "id": 62,
            "uuid": "a00a8835-5823-44a7-b1d6-aa7a1a49f09f",
            "shortUUID": "kLeg4YL1BVZXUx7sBFKzSx",
            "url": "https://dob.media.fibodo.com/videos/watch/a00a8835-5823-44a7-b1d6-aa7a1a49f09f",
            "name": "186 - Strong Circuit Training - Faye - 30mins",
            "category": {
                "id": 5,
                "label": "Sports"
            },
            "licence": {
                "id": 4,
                "label": "Attribution - Non Commercial"
            },
            "language": {
                "id": "en",
                "label": "English"
            },
            "privacy": {
                "id": 1,
                "label": "Public"
            },
            "nsfw": false,
            "truncatedDescription": null,
            "description": null,
            "isLocal": true,
            "duration": 1799,
            "aspectRatio": 1.7778,
            "views": 4,
            "viewers": 0,
            "likes": 0,
            "dislikes": 0,
            "thumbnailPath": "/lazy-static/thumbnails/4e2dbf53-04f1-44d3-9852-95c0d714dd5e.jpg",
            "previewPath": "/lazy-static/previews/8f8b39d8-c827-4c42-80b1-f4193522e81b.jpg",
            "embedPath": "/videos/embed/a00a8835-5823-44a7-b1d6-aa7a1a49f09f",
            "createdAt": "2024-05-27T17:20:21.272Z",
            "updatedAt": "2025-06-17T12:34:23.852Z",
            "publishedAt": "2024-05-29T15:45:49.674Z",
            "originallyPublishedAt": null,
            "isLive": false,
            "account": {
                "id": 7,
                "displayName": "DoingOurBit",
                "name": "d_o_b",
                "url": "https://dob.media.fibodo.com/accounts/d_o_b",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/39a47004-7915-46ee-bc6d-d3638300f51d.jpg",
                        "createdAt": "2024-05-27T04:53:46.090Z",
                        "updatedAt": "2024-05-27T04:53:46.090Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/4087d3ca-9e7c-46e5-a3ea-ca78e14affca.jpg",
                        "createdAt": "2024-05-27T04:53:46.167Z",
                        "updatedAt": "2024-05-27T04:53:46.167Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/6be0086f-1036-4546-8daf-e31c255a19f2.jpg",
                        "createdAt": "2024-05-27T04:53:46.170Z",
                        "updatedAt": "2024-05-27T04:53:46.170Z"
                    },
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/49f68f0b-810a-407a-ba01-99f7b1b35a05.jpg",
                        "createdAt": "2024-05-27T04:53:46.173Z",
                        "updatedAt": "2024-05-27T04:53:46.173Z"
                    }
                ]
            },
            "channel": {
                "id": 12,
                "name": "johnson_digital2",
                "displayName": "Johnson Digital Video’s",
                "url": "https://dob.media.fibodo.com/video-channels/johnson_digital2",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/378ceeb5-7346-4dbb-b433-509ef44a4581.png",
                        "createdAt": "2024-05-27T04:57:17.681Z",
                        "updatedAt": "2024-05-27T04:57:17.681Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/f6ac3eee-140c-4ee8-97d3-bc6a1b246dde.png",
                        "createdAt": "2024-05-27T04:57:17.701Z",
                        "updatedAt": "2024-05-27T04:57:17.701Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/9d9ae58a-5eff-42b7-8364-7e2cc10f45a1.png",
                        "createdAt": "2024-05-27T04:57:17.704Z",
                        "updatedAt": "2024-05-27T04:57:17.704Z"
                    },
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/45c4827a-2dfc-41d3-83ff-de23948620b9.png",
                        "createdAt": "2024-05-27T04:57:17.708Z",
                        "updatedAt": "2024-05-27T04:57:17.708Z"
                    }
                ]
            }
        },
        {
            "id": 55,
            "uuid": "c3ccb074-3939-450d-8a8d-3852ce08b227",
            "shortUUID": "qbkmcfenR7Lh6QViZuDt2Z",
            "url": "https://dob.media.fibodo.com/videos/watch/c3ccb074-3939-450d-8a8d-3852ce08b227",
            "name": "112 - Pilates - Michelle- 46mins - HD",
            "category": {
                "id": 5,
                "label": "Sports"
            },
            "licence": {
                "id": 4,
                "label": "Attribution - Non Commercial"
            },
            "language": {
                "id": "en",
                "label": "English"
            },
            "privacy": {
                "id": 1,
                "label": "Public"
            },
            "nsfw": false,
            "truncatedDescription": null,
            "description": null,
            "isLocal": true,
            "duration": 2741,
            "aspectRatio": 1.7778,
            "views": 12,
            "viewers": 0,
            "likes": 0,
            "dislikes": 0,
            "thumbnailPath": "/lazy-static/thumbnails/8893c626-4e70-401b-a738-84224217eb8c.jpg",
            "previewPath": "/lazy-static/previews/bbce8949-9323-4686-9792-2dff89fee1dc.jpg",
            "embedPath": "/videos/embed/c3ccb074-3939-450d-8a8d-3852ce08b227",
            "createdAt": "2024-05-27T13:02:50.970Z",
            "updatedAt": "2025-04-15T16:34:22.009Z",
            "publishedAt": "2024-05-29T15:44:37.091Z",
            "originallyPublishedAt": null,
            "isLive": false,
            "account": {
                "id": 7,
                "displayName": "DoingOurBit",
                "name": "d_o_b",
                "url": "https://dob.media.fibodo.com/accounts/d_o_b",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/4087d3ca-9e7c-46e5-a3ea-ca78e14affca.jpg",
                        "createdAt": "2024-05-27T04:53:46.167Z",
                        "updatedAt": "2024-05-27T04:53:46.167Z"
                    },
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/49f68f0b-810a-407a-ba01-99f7b1b35a05.jpg",
                        "createdAt": "2024-05-27T04:53:46.173Z",
                        "updatedAt": "2024-05-27T04:53:46.173Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/6be0086f-1036-4546-8daf-e31c255a19f2.jpg",
                        "createdAt": "2024-05-27T04:53:46.170Z",
                        "updatedAt": "2024-05-27T04:53:46.170Z"
                    },
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/39a47004-7915-46ee-bc6d-d3638300f51d.jpg",
                        "createdAt": "2024-05-27T04:53:46.090Z",
                        "updatedAt": "2024-05-27T04:53:46.090Z"
                    }
                ]
            },
            "channel": {
                "id": 12,
                "name": "johnson_digital2",
                "displayName": "Johnson Digital Video’s",
                "url": "https://dob.media.fibodo.com/video-channels/johnson_digital2",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/378ceeb5-7346-4dbb-b433-509ef44a4581.png",
                        "createdAt": "2024-05-27T04:57:17.681Z",
                        "updatedAt": "2024-05-27T04:57:17.681Z"
                    },
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/45c4827a-2dfc-41d3-83ff-de23948620b9.png",
                        "createdAt": "2024-05-27T04:57:17.708Z",
                        "updatedAt": "2024-05-27T04:57:17.708Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/9d9ae58a-5eff-42b7-8364-7e2cc10f45a1.png",
                        "createdAt": "2024-05-27T04:57:17.704Z",
                        "updatedAt": "2024-05-27T04:57:17.704Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/f6ac3eee-140c-4ee8-97d3-bc6a1b246dde.png",
                        "createdAt": "2024-05-27T04:57:17.701Z",
                        "updatedAt": "2024-05-27T04:57:17.701Z"
                    }
                ]
            }
        },
        {
            "id": 54,
            "uuid": "e266e305-4a40-4459-ad24-3673e2804dd1",
            "shortUUID": "tXvAexMmxk2Fzjeed2uD56",
            "url": "https://dob.media.fibodo.com/videos/watch/e266e305-4a40-4459-ad24-3673e2804dd1",
            "name": "147 - Cycle - Shinead - 27mins",
            "category": {
                "id": 5,
                "label": "Sports"
            },
            "licence": {
                "id": 4,
                "label": "Attribution - Non Commercial"
            },
            "language": {
                "id": "en",
                "label": "English"
            },
            "privacy": {
                "id": 1,
                "label": "Public"
            },
            "nsfw": false,
            "truncatedDescription": null,
            "description": null,
            "isLocal": true,
            "duration": 1632,
            "aspectRatio": 1.7778,
            "views": 2,
            "viewers": 0,
            "likes": 0,
            "dislikes": 0,
            "thumbnailPath": "/lazy-static/thumbnails/87dbaa28-3793-4366-909b-978065fccc5a.jpg",
            "previewPath": "/lazy-static/previews/bbefb118-f531-49fc-ad3e-dbc42d77cf43.jpg",
            "embedPath": "/videos/embed/e266e305-4a40-4459-ad24-3673e2804dd1",
            "createdAt": "2024-05-27T13:00:32.476Z",
            "updatedAt": "2024-10-19T08:53:48.436Z",
            "publishedAt": "2024-05-28T06:28:31.966Z",
            "originallyPublishedAt": null,
            "isLive": false,
            "account": {
                "id": 7,
                "displayName": "DoingOurBit",
                "name": "d_o_b",
                "url": "https://dob.media.fibodo.com/accounts/d_o_b",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/39a47004-7915-46ee-bc6d-d3638300f51d.jpg",
                        "createdAt": "2024-05-27T04:53:46.090Z",
                        "updatedAt": "2024-05-27T04:53:46.090Z"
                    },
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/49f68f0b-810a-407a-ba01-99f7b1b35a05.jpg",
                        "createdAt": "2024-05-27T04:53:46.173Z",
                        "updatedAt": "2024-05-27T04:53:46.173Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/6be0086f-1036-4546-8daf-e31c255a19f2.jpg",
                        "createdAt": "2024-05-27T04:53:46.170Z",
                        "updatedAt": "2024-05-27T04:53:46.170Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/4087d3ca-9e7c-46e5-a3ea-ca78e14affca.jpg",
                        "createdAt": "2024-05-27T04:53:46.167Z",
                        "updatedAt": "2024-05-27T04:53:46.167Z"
                    }
                ]
            },
            "channel": {
                "id": 12,
                "name": "johnson_digital2",
                "displayName": "Johnson Digital Video’s",
                "url": "https://dob.media.fibodo.com/video-channels/johnson_digital2",
                "host": "dob.media.fibodo.com",
                "avatars": [
                    {
                        "width": 1500,
                        "path": "/lazy-static/avatars/378ceeb5-7346-4dbb-b433-509ef44a4581.png",
                        "createdAt": "2024-05-27T04:57:17.681Z",
                        "updatedAt": "2024-05-27T04:57:17.681Z"
                    },
                    {
                        "width": 48,
                        "path": "/lazy-static/avatars/45c4827a-2dfc-41d3-83ff-de23948620b9.png",
                        "createdAt": "2024-05-27T04:57:17.708Z",
                        "updatedAt": "2024-05-27T04:57:17.708Z"
                    },
                    {
                        "width": 120,
                        "path": "/lazy-static/avatars/9d9ae58a-5eff-42b7-8364-7e2cc10f45a1.png",
                        "createdAt": "2024-05-27T04:57:17.704Z",
                        "updatedAt": "2024-05-27T04:57:17.704Z"
                    },
                    {
                        "width": 600,
                        "path": "/lazy-static/avatars/f6ac3eee-140c-4ee8-97d3-bc6a1b246dde.png",
                        "createdAt": "2024-05-27T04:57:17.701Z",
                        "updatedAt": "2024-05-27T04:57:17.701Z"
                    }
                ]
            }
        }
    ]';
            $data = json_decode($json, true);
            /*if ($channel == '') {
                $data['total'] = 19;
            }*/
            return $data;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
