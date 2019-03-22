import {
	__,
	Component,
	Fragment,
	Placeholder,
	Spinner,
	InspectorControls,
	PanelBody,
	QueryControls,
	ToggleControl,
	TextControl
} from '../wp-imports'

//import get from 'lodash/get';
//import { isUndefined, pickBy } from 'lodash';
import isUndefined from 'lodash/isUndefined';
import pickBy from 'lodash/pickBy';
import classnames from 'classnames';

import apiFetch from '@wordpress/api-fetch';
const { decodeEntities } = wp.htmlEntities;
const { addQueryArgs } = wp.url;
const { withSelect } = wp.data;

/**
 * Module Constants
 */
const CATEGORIES_LIST_QUERY = {
	per_page: 100,
};

class FAQsEdit extends Component {
  constructor() {
		super( ...arguments );
		this.state = {
			categoriesList: [],
		};
	}

	componentWillMount() {
		this.isStillMounted = true;
		this.fetchRequest = apiFetch( {
			path: addQueryArgs( '/wp-json/wp/v2/lh_faq_category/', CATEGORIES_LIST_QUERY ),
		} ).then(
			( categoriesList ) => {
				if ( this.isStillMounted ) {
					this.setState( { categoriesList } );
				}
			}
		).catch(
			() => {
				if ( this.isStillMounted ) {
					this.setState( { categoriesList: [] } );
				}
			}
		);
	}

	componentWillUnmount() {
		this.isStillMounted = false;
	}

	render () {

		const { attributes, setAttributes, latestPosts } = this.props;
		const { categoriesList } = this.state;
		const { order, orderBy, categories, postsToShow, allowCrossSite, crossSiteIds } = attributes;

		const inspectorControls = (
			<InspectorControls>
				<PanelBody title={ __( "FAQs Settings" ) }>
					<QueryControls
							{ ...{ order, orderBy } }
							numberOfItems={ postsToShow }
							categoriesList={ categoriesList }
							selectedCategoryId={ categories }
							onOrderChange={ ( value ) => setAttributes( { order: value } ) }
							onOrderByChange={ ( value ) => setAttributes( { orderBy: value } ) }
							onCategoryChange={ ( value ) => setAttributes( { categories: '' !== value ? value : undefined } ) }
							onNumberOfItemsChange={ ( value ) => setAttributes( { postsToShow: value } ) }
						/>
						<ToggleControl
							label="Enable cross site population?"
							help=""
							checked={ !! allowCrossSite }
							onChange={ ( value ) => { setAttributes( { allowCrossSite: ! allowCrossSite } ) } }
						/>
						{ !! allowCrossSite &&
							<TextControl
				        label="Cross site IDs"
				        value={ crossSiteIds }
				        onChange={ ( value ) => setAttributes( { crossSiteIds: value } ) }
					    />
						}
				</PanelBody>
			</InspectorControls>
		);

		const hasPosts = Array.isArray( latestPosts ) && latestPosts.length;
		if( ! hasPosts ){
			return (
				<Fragment>
					{ inspectorControls }
					<Placeholder
						icon="admin-post"
						label={ __( 'Latest FAQs' ) }
					>
						{ ! Array.isArray( latestPosts ) ?
							<Spinner /> :
							__( 'No FAQs found.' )
						}
					</Placeholder>
				</Fragment>
			);
		}

		const displayPosts = latestPosts.length > postsToShow ?
			latestPosts.slice( 0, postsToShow ) :
			latestPosts;

		return (
			<Fragment>
				{ inspectorControls }
				<ul className={ classnames( this.props.className ) }>
					{ displayPosts.map( ( post, i ) =>
						<li key={ i }>
							<h4>{ decodeEntities( post.title.rendered.trim() ) || __( '(Untitled)' ) }</h4>
							<div dangerouslySetInnerHTML={{ __html: post.excerpt.rendered.trim() }} />
						</li>
					) }
				</ul>
			</Fragment>
		)
	} //End Render

}

export default withSelect( ( select, props ) => {

	const { postsToShow, order, orderBy, categories, allowCrossSite, crossSiteIds } = props.attributes;
	const { getEntityRecords } = select( 'core' );

  const latestPostsQuery = pickBy( {
		lh_faq_category: categories,
		order,
		orderby: orderBy,
		per_page: postsToShow,
	}, ( value ) => ! isUndefined( value ) );
	return {
		latestPosts: getEntityRecords( 'postType', 'lh_faq', latestPostsQuery  ),
	};
})( FAQsEdit );
