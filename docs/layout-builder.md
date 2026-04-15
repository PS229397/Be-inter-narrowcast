# Layout Builder Contract

The admin layout builder stores its source of truth in `layouts.grid`.

## Persisted Shape

The root node is a JSON object with:

- `version`
- `id`
- `direction`
- `split`
- `children`
- `component`
- `componentType`
- `componentConfig`

Leaf nodes define which component is placed where. Split nodes define the layout tree.

## Component Keys

Base CMS components use stable keys such as:

- `text`
- `image`
- `video`
- `carousel`
- `ticker`
- `clock`
- `weather`
- `countdown`
- `qr`

Customer-specific components use the `custom:{id}` format.

## Future Consumer Contract

This structure is designed so future customer-side CMS flows, slide editors, and slideshow playback can:

- load the layout tree directly from `layouts.grid`
- determine which component belongs to each zone
- map slide content to layout node IDs
- extend component-specific rendering through `componentConfig`

The current phase only implements the admin builder. Customer-side rendering remains deferred.
