export const { registerBlockType } = wp.blocks

export const { __ } = wp.i18n


export const {
	PanelColor,
	RangeControl,
	SelectControl,
	TextControl,
	ToggleControl,
	CheckboxControl,
	BaseControl,
	Dashicon,
	IconButton,
	Button,
	Toolbar,
	withAPIData,
	withSelect,
	Spinner,
	QueryControls,
	Placeholder,
	PanelBody
} = wp.components

export const {
	withState,
} = wp.compose

export const {
	InspectorControls,
	BlockControls,
	ColorPalette,
	AlignmentToolbar,
	BlockAlignmentToolbar,
	RichText,
	UrlInput,
	MediaUpload,
} = wp.editor.InspectorControls ? wp.editor : wp.blocks

export const {
	Component,
	Fragment
} = wp.element;
