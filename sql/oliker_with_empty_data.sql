--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.10
-- Dumped by pg_dump version 9.5.10

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- Name: transactions_type; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE transactions_type AS ENUM (
    'AmountAddedToWallet',
    'AdminAddedAmountToUserWallet',
    'AdminDeductedAmountToUserWallet',
    'AdFeaturesUpdatedFee',
    'AdPackageFee',
    'WithdrawRequested',
    'WithdrawRequestApproved',
    'WithdrawRequestRejected'
);


ALTER TYPE transactions_type OWNER TO postgres;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: ad_extra_days; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ad_extra_days (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    ad_extra_id integer NOT NULL,
    category_id bigint DEFAULT '0'::bigint,
    days integer NOT NULL,
    amount double precision NOT NULL,
    is_active boolean DEFAULT true NOT NULL
);


ALTER TABLE ad_extra_days OWNER TO postgres;

--
-- Name: ad_extra_days_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ad_extra_days_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE ad_extra_days_id_seq OWNER TO postgres;

--
-- Name: ad_extra_days_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE ad_extra_days_id_seq OWNED BY ad_extra_days.id;


--
-- Name: ad_extras; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ad_extras (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    name character varying(255) NOT NULL,
    is_active boolean DEFAULT true NOT NULL
);


ALTER TABLE ad_extras OWNER TO postgres;

--
-- Name: ad_extras_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ad_extras_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE ad_extras_id_seq OWNER TO postgres;

--
-- Name: ad_extras_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE ad_extras_id_seq OWNED BY ad_extras.id;


--
-- Name: ad_favorites; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ad_favorites (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint NOT NULL,
    ad_id bigint NOT NULL,
    ip_id bigint DEFAULT (0)::bigint NOT NULL
);


ALTER TABLE ad_favorites OWNER TO postgres;

--
-- Name: ad_favorites_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ad_favorites_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE ad_favorites_id_seq OWNER TO postgres;

--
-- Name: ad_favorites_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE ad_favorites_id_seq OWNED BY ad_favorites.id;


--
-- Name: ad_form_fields; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ad_form_fields (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    ad_id bigint NOT NULL,
    form_field_id bigint NOT NULL,
    response text NOT NULL
);


ALTER TABLE ad_form_fields OWNER TO postgres;

--
-- Name: ad_form_fields_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ad_form_fields_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE ad_form_fields_id_seq OWNER TO postgres;

--
-- Name: ad_form_fields_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE ad_form_fields_id_seq OWNED BY ad_form_fields.id;


--
-- Name: ad_packages; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ad_packages (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    category_id bigint DEFAULT '0'::bigint,
    name character varying(255) NOT NULL,
    validity_days integer NOT NULL,
    amount double precision NOT NULL,
    additional_ads_allowed integer NOT NULL,
    is_unlimited_ads boolean DEFAULT false NOT NULL,
    credit_points integer NOT NULL,
    points_valid_days integer NOT NULL,
    is_active boolean DEFAULT true NOT NULL
);


ALTER TABLE ad_packages OWNER TO postgres;

--
-- Name: ad_packages_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ad_packages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE ad_packages_id_seq OWNER TO postgres;

--
-- Name: ad_packages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE ad_packages_id_seq OWNED BY ad_packages.id;


--
-- Name: ad_report_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ad_report_types (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    name character varying(255) NOT NULL
);


ALTER TABLE ad_report_types OWNER TO postgres;

--
-- Name: ad_report_types_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ad_report_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE ad_report_types_id_seq OWNER TO postgres;

--
-- Name: ad_report_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE ad_report_types_id_seq OWNED BY ad_report_types.id;


--
-- Name: ad_reports; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ad_reports (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    ad_id bigint NOT NULL,
    ad_report_type_id integer NOT NULL,
    message text NOT NULL,
    user_id bigint NOT NULL
);


ALTER TABLE ad_reports OWNER TO postgres;

--
-- Name: ad_reports_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ad_reports_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE ad_reports_id_seq OWNER TO postgres;

--
-- Name: ad_reports_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE ad_reports_id_seq OWNED BY ad_reports.id;


--
-- Name: ad_searches; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ad_searches (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint NOT NULL,
    keyword character varying(255) NOT NULL,
    category_id bigint DEFAULT '0'::bigint,
    is_search_in_description boolean DEFAULT false NOT NULL,
    is_only_ads_with_images boolean DEFAULT false NOT NULL,
    is_notify_whenever_new_ads_posted boolean DEFAULT true NOT NULL,
    ip_id bigint DEFAULT (0)::bigint NOT NULL
);


ALTER TABLE ad_searches OWNER TO postgres;

--
-- Name: ad_searches_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ad_searches_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE ad_searches_id_seq OWNER TO postgres;

--
-- Name: ad_searches_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE ad_searches_id_seq OWNED BY ad_searches.id;


--
-- Name: ad_views; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ad_views (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint,
    ad_id bigint NOT NULL,
    ip_id bigint DEFAULT (0)::bigint NOT NULL
);


ALTER TABLE ad_views OWNER TO postgres;

--
-- Name: ad_views_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ad_views_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE ad_views_id_seq OWNER TO postgres;

--
-- Name: ad_views_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE ad_views_id_seq OWNED BY ad_views.id;


--
-- Name: ads; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ads (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint NOT NULL,
    title character varying(255) NOT NULL,
    slug character varying(265) NOT NULL,
    category_id bigint DEFAULT '0'::bigint,
    advertiser_type_id integer NOT NULL,
    is_an_exchange_item boolean DEFAULT false NOT NULL,
    price double precision NOT NULL,
    is_negotiable boolean DEFAULT false NOT NULL,
    description text NOT NULL,
    city_id bigint DEFAULT (0)::bigint NOT NULL,
    state_id bigint DEFAULT (0)::bigint NOT NULL,
    country_id integer DEFAULT 0 NOT NULL,
    location character varying(255),
    is_send_email_when_user_contact boolean DEFAULT false NOT NULL,
    latitude double precision DEFAULT (0)::double precision NOT NULL,
    longitude double precision DEFAULT (0)::double precision NOT NULL,
    hash character varying(50),
    is_show_as_top_ads boolean DEFAULT false NOT NULL,
    advertiser_name character varying(255) NOT NULL,
    ad_in_top_end_date timestamp without time zone,
    phone_number character varying(50) NOT NULL,
    top_ads_end_date timestamp without time zone,
    is_show_ad_in_top boolean DEFAULT false NOT NULL,
    urgent_end_date timestamp without time zone,
    highlighted_end_date timestamp without time zone,
    is_removed_by_admin boolean DEFAULT false NOT NULL,
    is_urgent boolean DEFAULT false NOT NULL,
    is_highlighted boolean DEFAULT false NOT NULL,
    ad_view_count bigint DEFAULT (0)::bigint NOT NULL,
    ad_favorite_count bigint DEFAULT (0)::bigint NOT NULL,
    message_count bigint DEFAULT (0)::bigint,
    is_active boolean NOT NULL,
    is_price_reduced boolean DEFAULT false NOT NULL,
    ad_start_date timestamp without time zone,
    ad_end_date timestamp without time zone,
    payment_gateway_id integer,
    pending_payment_log character varying(255),
    paypal_pay_key character varying(255),
    ad_report_count bigint DEFAULT '0'::bigint NOT NULL
);


ALTER TABLE ads OWNER TO postgres;

--
-- Name: ads_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ads_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE ads_id_seq OWNER TO postgres;

--
-- Name: ads_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE ads_id_seq OWNED BY ads.id;


--
-- Name: advertiser_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE advertiser_types (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    name character varying(255) NOT NULL
);


ALTER TABLE advertiser_types OWNER TO postgres;

--
-- Name: advertiser_types_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE advertiser_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE advertiser_types_id_seq OWNER TO postgres;

--
-- Name: advertiser_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE advertiser_types_id_seq OWNED BY advertiser_types.id;


--
-- Name: attachments_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE attachments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE attachments_id_seq OWNER TO postgres;

--
-- Name: attachments; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE attachments (
    id bigint DEFAULT nextval('attachments_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    class character varying(255) NOT NULL,
    foreign_id bigint NOT NULL,
    filename character varying(255) NOT NULL,
    dir character varying(255) NOT NULL,
    mimetype character varying(255),
    filesize bigint DEFAULT (0)::bigint NOT NULL,
    height bigint DEFAULT (0)::bigint NOT NULL,
    width bigint DEFAULT (0)::bigint NOT NULL,
    CONSTRAINT attachments_foreign_id_check CHECK ((foreign_id >= 0))
);


ALTER TABLE attachments OWNER TO postgres;

--
-- Name: banned_ips_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE banned_ips_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE banned_ips_id_seq OWNER TO postgres;

--
-- Name: banned_ips; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE banned_ips (
    id bigint DEFAULT nextval('banned_ips_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    address character varying(255) NOT NULL,
    range text,
    reason character varying(255),
    redirect character varying(255),
    thetime integer NOT NULL,
    timespan integer NOT NULL
);


ALTER TABLE banned_ips OWNER TO postgres;

--
-- Name: categories; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE categories (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    name character varying(255) NOT NULL,
    slug character varying(265) NOT NULL,
    parent_id bigint DEFAULT '0'::bigint,
    allowed_free_ads_count integer NOT NULL,
    post_ad_fee double precision NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    ad_count bigint DEFAULT (0)::bigint NOT NULL,
    form_field_count bigint DEFAULT (0)::bigint,
    ad_extra_day_count bigint DEFAULT '0'::bigint NOT NULL,
    description text,
    is_popular boolean DEFAULT false NOT NULL,
    allowed_days_to_display_ad bigint DEFAULT '0'::bigint NOT NULL
);


ALTER TABLE categories OWNER TO postgres;

--
-- Name: categories_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE categories_id_seq OWNER TO postgres;

--
-- Name: categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE categories_id_seq OWNED BY categories.id;


--
-- Name: cities_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE cities_id_seq OWNER TO postgres;

--
-- Name: cities; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE cities (
    id bigint DEFAULT nextval('cities_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    country_id bigint NOT NULL,
    state_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    slug character varying(265) NOT NULL,
    city_code character varying DEFAULT ''::character varying,
    is_active boolean DEFAULT true NOT NULL
);


ALTER TABLE cities OWNER TO postgres;

--
-- Name: contacts_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE contacts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE contacts_id_seq OWNER TO postgres;

--
-- Name: contacts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE contacts (
    id bigint DEFAULT nextval('contacts_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    first_name character varying(150) NOT NULL,
    last_name character varying(150) NOT NULL,
    email character varying(150) NOT NULL,
    phone character varying(20) NOT NULL,
    subject text NOT NULL,
    message text NOT NULL,
    ip_id bigint NOT NULL
);


ALTER TABLE contacts OWNER TO postgres;

--
-- Name: countries_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE countries_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE countries_id_seq OWNER TO postgres;

--
-- Name: countries; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE countries (
    id integer DEFAULT nextval('countries_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    iso_alpha2 character varying(2) DEFAULT 'NULL'::character varying,
    iso_alpha3 character varying(3) DEFAULT 'NULL'::character varying,
    iso_numeric integer,
    fips_code character varying(3),
    name character varying(200) NOT NULL,
    capital character varying(200),
    areainsqkm double precision,
    population integer,
    continent character varying(2) DEFAULT 'NULL'::character varying,
    tld character varying(3) DEFAULT 'NULL'::character varying,
    currency character varying(3) DEFAULT 'NULL'::character varying,
    currencyname character varying(20) DEFAULT 'NULL'::character varying,
    phone character varying(10) DEFAULT 'NULL'::character varying,
    postalcodeformat character varying(20) DEFAULT 'NULL'::character varying,
    postalcoderegex character varying(20) DEFAULT 'NULL'::character varying,
    languages character varying(200),
    geonameid integer,
    neighbours character varying(20) DEFAULT 'NULL'::character varying,
    equivalentfipscode character varying(10) DEFAULT 'NULL'::character varying
);


ALTER TABLE countries OWNER TO postgres;

--
-- Name: email_templates_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE email_templates_id_seq
    START WITH 3
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE email_templates_id_seq OWNER TO postgres;

--
-- Name: email_templates; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE email_templates (
    id bigint DEFAULT nextval('email_templates_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    name character varying(255) NOT NULL,
    display_name character varying(265) NOT NULL,
    description text NOT NULL,
    "from" character varying(100) NOT NULL,
    reply_to character varying(100) NOT NULL,
    subject character varying(300) NOT NULL,
    email_variables character varying(500) NOT NULL,
    html_email_content text NOT NULL,
    text_email_content text NOT NULL
);


ALTER TABLE email_templates OWNER TO postgres;

--
-- Name: form_fields; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE form_fields (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    category_id bigint DEFAULT '0'::bigint,
    name character varying(255) NOT NULL,
    display_name character varying(255) NOT NULL,
    label character varying(255) NOT NULL,
    input_type_id integer NOT NULL,
    info character varying(255),
    is_required boolean NOT NULL,
    depends_on character varying(255),
    depend_value character varying(255),
    display_order integer DEFAULT 0 NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    options text
);


ALTER TABLE form_fields OWNER TO postgres;

--
-- Name: form_fields_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE form_fields_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE form_fields_id_seq OWNER TO postgres;

--
-- Name: form_fields_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE form_fields_id_seq OWNED BY form_fields.id;


--
-- Name: input_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE input_types (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    name character varying(255) NOT NULL
);


ALTER TABLE input_types OWNER TO postgres;

--
-- Name: input_types_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE input_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE input_types_id_seq OWNER TO postgres;

--
-- Name: input_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE input_types_id_seq OWNED BY input_types.id;


--
-- Name: ips_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ips_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE ips_id_seq OWNER TO postgres;

--
-- Name: ips; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ips (
    id bigint DEFAULT nextval('ips_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    ip character varying(255) NOT NULL,
    host character varying(255) NOT NULL,
    city_id bigint,
    state_id bigint,
    country_id bigint,
    timezone_id bigint DEFAULT (0)::bigint NOT NULL,
    latitude double precision DEFAULT (0)::double precision NOT NULL,
    longitude double precision DEFAULT (0)::double precision NOT NULL
);


ALTER TABLE ips OWNER TO postgres;

--
-- Name: languages_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE languages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE languages_id_seq OWNER TO postgres;

--
-- Name: languages; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE languages (
    id bigint DEFAULT nextval('languages_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    name character varying(255) NOT NULL,
    iso2 character(2) NOT NULL,
    iso3 character(3) NOT NULL,
    is_active boolean DEFAULT true NOT NULL
);


ALTER TABLE languages OWNER TO postgres;

--
-- Name: message_contents; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE message_contents (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    subject character varying(255) NOT NULL,
    message text NOT NULL
);


ALTER TABLE message_contents OWNER TO postgres;

--
-- Name: message_contents_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE message_contents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE message_contents_id_seq OWNER TO postgres;

--
-- Name: message_contents_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE message_contents_id_seq OWNED BY message_contents.id;


--
-- Name: messages; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE messages (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint NOT NULL,
    other_user_id bigint NOT NULL,
    ad_id bigint NOT NULL,
    message_content_id bigint NOT NULL,
    is_sender boolean DEFAULT false NOT NULL,
    is_read boolean DEFAULT false NOT NULL,
    is_archived boolean DEFAULT false NOT NULL
);


ALTER TABLE messages OWNER TO postgres;

--
-- Name: messages_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE messages_id_seq OWNER TO postgres;

--
-- Name: messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE messages_id_seq OWNED BY messages.id;


--
-- Name: money_transfer_account_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE money_transfer_account_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE money_transfer_account_id_seq OWNER TO postgres;

--
-- Name: money_transfer_accounts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE money_transfer_accounts (
    id bigint DEFAULT nextval('money_transfer_account_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint NOT NULL,
    account text NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    is_primary boolean DEFAULT false NOT NULL
);


ALTER TABLE money_transfer_accounts OWNER TO postgres;

--
-- Name: oauth_access_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE oauth_access_tokens (
    access_token character varying(40) NOT NULL,
    client_id character varying(80),
    user_id character varying(255),
    expires timestamp without time zone,
    scope text
);


ALTER TABLE oauth_access_tokens OWNER TO postgres;

--
-- Name: oauth_authorization_codes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE oauth_authorization_codes (
    authorization_code character varying(40) NOT NULL,
    client_id character varying(80),
    user_id character varying(255),
    redirect_uri character varying(2000),
    expires timestamp without time zone,
    scope character varying(2000)
);


ALTER TABLE oauth_authorization_codes OWNER TO postgres;

--
-- Name: oauth_clients_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE oauth_clients_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE oauth_clients_id_seq OWNER TO postgres;

--
-- Name: oauth_clients; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE oauth_clients (
    id integer DEFAULT nextval('oauth_clients_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    user_id character varying(80),
    client_name character varying(255),
    client_id character varying(80) NOT NULL,
    client_secret character varying(80),
    redirect_uri character varying(2000),
    grant_types character varying(80),
    scope character varying(100),
    client_url character varying(255),
    logo_url character varying(255),
    tos_url character varying(255),
    policy_url character varying(2000)
);


ALTER TABLE oauth_clients OWNER TO postgres;

--
-- Name: oauth_jwt; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE oauth_jwt (
    client_id character varying(80) NOT NULL,
    subject character varying(80),
    public_key character varying(2000)
);


ALTER TABLE oauth_jwt OWNER TO postgres;

--
-- Name: oauth_refresh_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE oauth_refresh_tokens (
    refresh_token character varying(40) NOT NULL,
    client_id character varying(80),
    user_id character varying(255),
    expires timestamp without time zone,
    scope text
);


ALTER TABLE oauth_refresh_tokens OWNER TO postgres;

--
-- Name: oauth_scopes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE oauth_scopes (
    scope text NOT NULL,
    is_default boolean
);


ALTER TABLE oauth_scopes OWNER TO postgres;

--
-- Name: pages_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE pages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE pages_id_seq OWNER TO postgres;

--
-- Name: pages; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE pages (
    id bigint DEFAULT nextval('pages_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    title character varying(255) NOT NULL,
    slug character varying(265) NOT NULL,
    content text NOT NULL,
    meta_keywords character varying(255),
    meta_description text,
    is_active boolean DEFAULT true NOT NULL
);


ALTER TABLE pages OWNER TO postgres;

--
-- Name: payment_gateway_settings_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE payment_gateway_settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE payment_gateway_settings_id_seq OWNER TO postgres;

--
-- Name: payment_gateway_settings; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE payment_gateway_settings (
    id bigint DEFAULT nextval('payment_gateway_settings_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    payment_gateway_id integer NOT NULL,
    name character varying(256) NOT NULL,
    label character varying(512) NOT NULL,
    description text NOT NULL,
    type character varying(8),
    options text NOT NULL,
    test_mode_value text,
    live_mode_value text
);


ALTER TABLE payment_gateway_settings OWNER TO postgres;

--
-- Name: payment_gateways_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE payment_gateways_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE payment_gateways_id_seq OWNER TO postgres;

--
-- Name: payment_gateways; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE payment_gateways (
    id bigint DEFAULT nextval('payment_gateways_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    name character varying(255) NOT NULL,
    display_name character varying(255) NOT NULL,
    slug character varying(255),
    description text NOT NULL,
    gateway_fees double precision NOT NULL,
    transaction_count integer DEFAULT 0 NOT NULL,
    payment_gateway_setting_count integer DEFAULT 0 NOT NULL,
    is_test_mode boolean DEFAULT true NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    is_enable_for_wallet boolean DEFAULT false NOT NULL
);


ALTER TABLE payment_gateways OWNER TO postgres;

--
-- Name: provider_users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE provider_users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE provider_users_id_seq OWNER TO postgres;

--
-- Name: provider_users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE provider_users (
    id bigint DEFAULT nextval('provider_users_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint NOT NULL,
    provider_id bigint NOT NULL,
    access_token character varying(255) NOT NULL,
    access_token_secret character varying(255),
    foreign_id character varying(255),
    profile_picture_url character varying(255),
    is_connected boolean DEFAULT true NOT NULL
);


ALTER TABLE provider_users OWNER TO postgres;

--
-- Name: providers_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE providers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE providers_id_seq OWNER TO postgres;

--
-- Name: providers; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE providers (
    id bigint DEFAULT nextval('providers_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    name character varying(255),
    slug character varying(265) NOT NULL,
    secret_key character varying(255),
    api_key character varying(255),
    icon_class character varying(255),
    button_class character varying(255),
    is_active boolean DEFAULT true NOT NULL,
    "position" bigint
);


ALTER TABLE providers OWNER TO postgres;

--
-- Name: roles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE roles (
    id bigint NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    name character varying(50) NOT NULL,
    is_active boolean NOT NULL
);


ALTER TABLE roles OWNER TO postgres;

--
-- Name: search_keywords_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE search_keywords_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE search_keywords_id_seq OWNER TO postgres;

--
-- Name: search_keywords; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE search_keywords (
    id bigint DEFAULT nextval('search_keywords_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    keyword character varying(255) NOT NULL,
    search_log_count bigint DEFAULT 0 NOT NULL
);


ALTER TABLE search_keywords OWNER TO postgres;

--
-- Name: setting_categories_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE setting_categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE setting_categories_id_seq OWNER TO postgres;

--
-- Name: setting_categories; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE setting_categories (
    id bigint DEFAULT nextval('setting_categories_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    name character varying(200) NOT NULL,
    description text NOT NULL
);


ALTER TABLE setting_categories OWNER TO postgres;

--
-- Name: settings_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE settings_id_seq OWNER TO postgres;

--
-- Name: settings; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE settings (
    id bigint DEFAULT nextval('settings_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    setting_category_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    value text,
    description text,
    type character varying(8) NOT NULL,
    label character varying(255),
    "position" integer NOT NULL,
    options text
);


ALTER TABLE settings OWNER TO postgres;

--
-- Name: states_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE states_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE states_id_seq OWNER TO postgres;

--
-- Name: states; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE states (
    id bigint DEFAULT nextval('states_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    country_id bigint NOT NULL,
    name character varying(80),
    slug character varying(100),
    state_code character varying(5),
    is_active boolean DEFAULT true NOT NULL,
    CONSTRAINT states_country_id_check CHECK ((country_id >= 0))
);


ALTER TABLE states OWNER TO postgres;

--
-- Name: transaction_types_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE transaction_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE transaction_types_id_seq OWNER TO postgres;

--
-- Name: transaction_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE transaction_types (
    id integer DEFAULT nextval('transaction_types_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    name character varying(255) NOT NULL,
    is_credit boolean NOT NULL,
    message character varying(255) NOT NULL,
    message_for_other_user character varying(255) NOT NULL,
    message_for_admin character varying(255) NOT NULL
);


ALTER TABLE transaction_types OWNER TO postgres;

--
-- Name: transactions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE transactions (
    id bigint NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint NOT NULL,
    to_user_id bigint NOT NULL,
    foreign_id bigint NOT NULL,
    class character varying(255) NOT NULL,
    payment_gateway_id bigint,
    amount double precision NOT NULL,
    site_revenue double precision DEFAULT '0'::double precision NOT NULL,
    type transactions_type NOT NULL
);


ALTER TABLE transactions OWNER TO postgres;

--
-- Name: COLUMN transactions.class; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN transactions.class IS '1.Ad, 2.UserCashWithdrawals, 3.Wallet, 4.UserAdPackage, 5.UserAdExtra';


--
-- Name: COLUMN transactions.type; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN transactions.type IS '1.AmountAddedToWallet,     2.AdminAddedAmountToUserWallet,     3.AdminDeductedAmountToUserWallet,     4.AdFeaturesUpdatedFee,     5.AdPackageFee,     6.WithdrawRequested,     7.WithdrawRequestApproved,     8.WithdrawRequestRejected';


--
-- Name: transactions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE transactions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE transactions_id_seq OWNER TO postgres;

--
-- Name: transactions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE transactions_id_seq OWNED BY transactions.id;


--
-- Name: user_ad_extras; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE user_ad_extras (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint NOT NULL,
    ad_id bigint NOT NULL,
    ad_extra_id integer NOT NULL,
    ad_extra_day_id integer NOT NULL,
    amount double precision NOT NULL,
    payment_gateway_id integer NOT NULL,
    is_payment_completed boolean DEFAULT false NOT NULL,
    paypal_pay_key character varying(255)
);


ALTER TABLE user_ad_extras OWNER TO postgres;

--
-- Name: user_ad_extras_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE user_ad_extras_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE user_ad_extras_id_seq OWNER TO postgres;

--
-- Name: user_ad_extras_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE user_ad_extras_id_seq OWNED BY user_ad_extras.id;


--
-- Name: user_ad_packages_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE user_ad_packages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE user_ad_packages_id_seq OWNER TO postgres;

--
-- Name: user_ad_packages; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE user_ad_packages (
    id integer DEFAULT nextval('user_ad_packages_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint NOT NULL,
    ad_package_id integer NOT NULL,
    allowed_ad_count integer DEFAULT 0 NOT NULL,
    points integer DEFAULT 0 NOT NULL,
    used_points integer DEFAULT 0 NOT NULL,
    expiry_date timestamp without time zone NOT NULL,
    amount double precision DEFAULT (0)::double precision NOT NULL,
    payment_gateway_id integer NOT NULL,
    is_payment_completed boolean DEFAULT false NOT NULL,
    used_ad_count bigint DEFAULT '0'::bigint NOT NULL,
    paypal_pay_key character varying(250)
);


ALTER TABLE user_ad_packages OWNER TO postgres;

--
-- Name: user_cash_withdrawals; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE user_cash_withdrawals (
    id bigint NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint NOT NULL,
    money_transfer_account_id bigint NOT NULL,
    withdrawal_status_id integer NOT NULL,
    amount double precision NOT NULL,
    remark text
);


ALTER TABLE user_cash_withdrawals OWNER TO postgres;

--
-- Name: user_cash_withdrawals_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE user_cash_withdrawals_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE user_cash_withdrawals_id_seq OWNER TO postgres;

--
-- Name: user_cash_withdrawals_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE user_cash_withdrawals_id_seq OWNED BY user_cash_withdrawals.id;


--
-- Name: user_notifications; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE user_notifications (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint NOT NULL,
    is_new_messages_received_notification_to_sms boolean DEFAULT true NOT NULL,
    is_new_messages_received_notification_to_email boolean DEFAULT true NOT NULL,
    is_new_ads_on_saved_searches_to_sms boolean DEFAULT true NOT NULL,
    is_new_ads_on_saved_searches_to_email boolean DEFAULT true NOT NULL,
    is_price_reduced_on_favorite_ads_to_sms boolean DEFAULT true NOT NULL,
    is_price_reduced_on_favorite_ads_to_email boolean DEFAULT true NOT NULL
);


ALTER TABLE user_notifications OWNER TO postgres;

--
-- Name: user_notifications_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE user_notifications_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE user_notifications_id_seq OWNER TO postgres;

--
-- Name: user_notifications_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE user_notifications_id_seq OWNED BY user_notifications.id;


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE users_id_seq OWNER TO postgres;

--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE users (
    id bigint DEFAULT nextval('users_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    role_id integer DEFAULT 2 NOT NULL,
    username character varying(255) NOT NULL,
    email character varying(256) NOT NULL,
    password character varying(256) NOT NULL,
    provider_id bigint DEFAULT (0)::bigint NOT NULL,
    first_name character varying(150),
    last_name character varying(150),
    gender_id smallint,
    dob date,
    about_me text,
    address character varying(255),
    address1 character varying(255),
    city_id bigint DEFAULT (0)::bigint NOT NULL,
    state_id bigint DEFAULT (0)::bigint NOT NULL,
    country_id bigint DEFAULT (0)::bigint NOT NULL,
    zip_code character varying(50),
    latitude numeric(10,6) DEFAULT (0)::numeric NOT NULL,
    longitude numeric(10,6) DEFAULT (0)::numeric NOT NULL,
    phone character varying(20),
    mobile character varying(15),
    available_wallet_amount double precision DEFAULT (0)::double precision NOT NULL,
    available_points double precision DEFAULT '0'::double precision NOT NULL,
    billing_company_name character varying(255),
    billing_address character varying(255),
    billing_postal_code_1 character varying(4),
    billing_postal_code_2 character varying(3),
    billing_city character varying(255),
    billing_tin character varying(255),
    invoice_name character varying(255),
    invoice_address character varying(255),
    invoice_postal_code_1 character varying(4),
    invoice_postal_code_2 character varying(3),
    invoice_city character varying(255),
    last_login_ip_id character varying(30) DEFAULT '0'::character varying NOT NULL,
    last_logged_in_time timestamp without time zone,
    is_active boolean DEFAULT false NOT NULL,
    is_email_confirmed boolean DEFAULT false NOT NULL,
    is_agree_terms_conditions boolean DEFAULT false NOT NULL,
    is_subscribed boolean DEFAULT false NOT NULL,
    is_turn_off_automatic_fields boolean DEFAULT false NOT NULL,
    is_hide_my_ads boolean DEFAULT false NOT NULL,
    ad_count bigint DEFAULT (0)::bigint NOT NULL,
    message_count bigint DEFAULT (0)::bigint NOT NULL,
    ad_search_count bigint DEFAULT (0)::bigint NOT NULL,
    ad_favorite_count bigint DEFAULT (0)::bigint NOT NULL,
    ad_active_count bigint DEFAULT (0)::bigint NOT NULL,
    hash character varying(50) DEFAULT ''::character varying NOT NULL
);


ALTER TABLE users OWNER TO postgres;

--
-- Name: COLUMN users.role_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN users.role_id IS '1 - Admin, 2 - User';


--
-- Name: vaults; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE vaults (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    masked_cc character varying(100) NOT NULL,
    credit_card_type character varying(100) NOT NULL,
    vault_key character varying(100),
    vault_id bigint,
    user_id bigint NOT NULL,
    email character varying(200),
    address text,
    city character varying(100),
    state character varying(100),
    country character varying(100),
    zip_code character varying(100),
    phone character varying(100),
    is_primary boolean DEFAULT true,
    credit_card_expire character varying(100),
    expire_month integer,
    expire_year integer,
    cvv2 character varying(10),
    first_name character varying(100),
    last_name character varying(100),
    payment_type smallint DEFAULT '1'::smallint NOT NULL
);


ALTER TABLE vaults OWNER TO postgres;

--
-- Name: vaults_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE vaults_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE vaults_id_seq OWNER TO postgres;

--
-- Name: vaults_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE vaults_id_seq OWNED BY vaults.id;


--
-- Name: wallet_transaction_logs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE wallet_transaction_logs (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    amount double precision DEFAULT '0'::double precision NOT NULL,
    foreign_id bigint NOT NULL,
    class character varying(255) NOT NULL,
    status character varying(255) NOT NULL,
    payment_type character varying(255) NOT NULL
);


ALTER TABLE wallet_transaction_logs OWNER TO postgres;

--
-- Name: wallet_transaction_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE wallet_transaction_logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE wallet_transaction_logs_id_seq OWNER TO postgres;

--
-- Name: wallet_transaction_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE wallet_transaction_logs_id_seq OWNED BY wallet_transaction_logs.id;


--
-- Name: wallets; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE wallets (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint NOT NULL,
    amount double precision NOT NULL,
    payment_gateway_id smallint NOT NULL,
    is_payment_completed boolean DEFAULT false NOT NULL,
    paypal_pay_key character varying(250)
);


ALTER TABLE wallets OWNER TO postgres;

--
-- Name: wallets_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE wallets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE wallets_id_seq OWNER TO postgres;

--
-- Name: wallets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE wallets_id_seq OWNED BY wallets.id;


--
-- Name: withdrawal_statuses; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE withdrawal_statuses (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    name character varying(255) NOT NULL
);


ALTER TABLE withdrawal_statuses OWNER TO postgres;

--
-- Name: zazpay_ipn_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE zazpay_ipn_logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE zazpay_ipn_logs_id_seq OWNER TO postgres;

--
-- Name: zazpay_ipn_logs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE zazpay_ipn_logs (
    id bigint DEFAULT nextval('zazpay_ipn_logs_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    ip bigint NOT NULL,
    post_variable text NOT NULL
);


ALTER TABLE zazpay_ipn_logs OWNER TO postgres;

--
-- Name: zazpay_payment_gateways_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE zazpay_payment_gateways_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE zazpay_payment_gateways_id_seq OWNER TO postgres;

--
-- Name: zazpay_payment_gateways; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE zazpay_payment_gateways (
    id bigint DEFAULT nextval('zazpay_payment_gateways_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    zazpay_gateway_name character varying(255) NOT NULL,
    zazpay_gateway_id bigint NOT NULL,
    zazpay_payment_group_id bigint NOT NULL,
    zazpay_gateway_details text NOT NULL,
    is_marketplace_supported boolean NOT NULL
);


ALTER TABLE zazpay_payment_gateways OWNER TO postgres;

--
-- Name: zazpay_payment_gateways_users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE zazpay_payment_gateways_users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE zazpay_payment_gateways_users_id_seq OWNER TO postgres;

--
-- Name: zazpay_payment_gateways_users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE zazpay_payment_gateways_users (
    id bigint DEFAULT nextval('zazpay_payment_gateways_users_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint NOT NULL,
    zazpay_payment_gateway_id bigint NOT NULL
);


ALTER TABLE zazpay_payment_gateways_users OWNER TO postgres;

--
-- Name: zazpay_payment_groups_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE zazpay_payment_groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE zazpay_payment_groups_id_seq OWNER TO postgres;

--
-- Name: zazpay_payment_groups; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE zazpay_payment_groups (
    id bigint DEFAULT nextval('zazpay_payment_groups_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    zazpay_group_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    thumb_url text NOT NULL
);


ALTER TABLE zazpay_payment_groups OWNER TO postgres;

--
-- Name: zazpay_transaction_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE zazpay_transaction_logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE zazpay_transaction_logs_id_seq OWNER TO postgres;

--
-- Name: zazpay_transaction_logs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE zazpay_transaction_logs (
    id bigint DEFAULT nextval('zazpay_transaction_logs_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    class character varying(50) NOT NULL,
    foreign_id bigint NOT NULL,
    zazpay_pay_key character varying(255) NOT NULL,
    merchant_id bigint NOT NULL,
    gateway_id bigint NOT NULL,
    status character varying(50) NOT NULL,
    payment_type character varying(50) NOT NULL,
    buyer_id bigint NOT NULL,
    buyer_email character varying(255) NOT NULL,
    buyer_address character varying(255) NOT NULL,
    amount double precision DEFAULT (0)::double precision NOT NULL,
    payment_id bigint DEFAULT (0)::bigint NOT NULL
);


ALTER TABLE zazpay_transaction_logs OWNER TO postgres;

--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_extra_days ALTER COLUMN id SET DEFAULT nextval('ad_extra_days_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_extras ALTER COLUMN id SET DEFAULT nextval('ad_extras_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_favorites ALTER COLUMN id SET DEFAULT nextval('ad_favorites_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_form_fields ALTER COLUMN id SET DEFAULT nextval('ad_form_fields_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_packages ALTER COLUMN id SET DEFAULT nextval('ad_packages_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_report_types ALTER COLUMN id SET DEFAULT nextval('ad_report_types_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_reports ALTER COLUMN id SET DEFAULT nextval('ad_reports_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_searches ALTER COLUMN id SET DEFAULT nextval('ad_searches_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_views ALTER COLUMN id SET DEFAULT nextval('ad_views_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ads ALTER COLUMN id SET DEFAULT nextval('ads_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY advertiser_types ALTER COLUMN id SET DEFAULT nextval('advertiser_types_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY categories ALTER COLUMN id SET DEFAULT nextval('categories_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY form_fields ALTER COLUMN id SET DEFAULT nextval('form_fields_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY input_types ALTER COLUMN id SET DEFAULT nextval('input_types_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY message_contents ALTER COLUMN id SET DEFAULT nextval('message_contents_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY messages ALTER COLUMN id SET DEFAULT nextval('messages_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY transactions ALTER COLUMN id SET DEFAULT nextval('transactions_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_ad_extras ALTER COLUMN id SET DEFAULT nextval('user_ad_extras_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_cash_withdrawals ALTER COLUMN id SET DEFAULT nextval('user_cash_withdrawals_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_notifications ALTER COLUMN id SET DEFAULT nextval('user_notifications_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY vaults ALTER COLUMN id SET DEFAULT nextval('vaults_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY wallet_transaction_logs ALTER COLUMN id SET DEFAULT nextval('wallet_transaction_logs_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY wallets ALTER COLUMN id SET DEFAULT nextval('wallets_id_seq'::regclass);


--
-- Data for Name: ad_extra_days; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY ad_extra_days (id, created_at, updated_at, ad_extra_id, category_id, days, amount, is_active) FROM stdin;
\.


--
-- Name: ad_extra_days_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('ad_extra_days_id_seq', 1, false);


--
-- Data for Name: ad_extras; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY ad_extras (id, created_at, updated_at, name, is_active) FROM stdin;
1	2016-08-13 08:55:34	2016-08-13 08:55:34	Top Ads	t
2	2016-08-13 08:55:39	2016-08-13 08:55:39	Highlight	t
3	2016-08-13 08:55:59	2016-08-13 08:55:59	Urgent	t
4	2016-08-13 08:56:06	2016-08-13 08:56:06	In Top	t
\.


--
-- Name: ad_extras_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('ad_extras_id_seq', 4, true);


--
-- Data for Name: ad_favorites; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY ad_favorites (id, created_at, updated_at, user_id, ad_id, ip_id) FROM stdin;
\.


--
-- Name: ad_favorites_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('ad_favorites_id_seq', 1, false);


--
-- Data for Name: ad_form_fields; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY ad_form_fields (id, created_at, updated_at, ad_id, form_field_id, response) FROM stdin;
\.


--
-- Name: ad_form_fields_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('ad_form_fields_id_seq', 1, false);


--
-- Data for Name: ad_packages; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY ad_packages (id, created_at, updated_at, category_id, name, validity_days, amount, additional_ads_allowed, is_unlimited_ads, credit_points, points_valid_days, is_active) FROM stdin;
\.


--
-- Name: ad_packages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('ad_packages_id_seq', 1, false);


--
-- Data for Name: ad_report_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY ad_report_types (id, created_at, updated_at, name) FROM stdin;
\.


--
-- Name: ad_report_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('ad_report_types_id_seq', 1, false);


--
-- Data for Name: ad_reports; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY ad_reports (id, created_at, updated_at, ad_id, ad_report_type_id, message, user_id) FROM stdin;
\.


--
-- Name: ad_reports_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('ad_reports_id_seq', 1, false);


--
-- Data for Name: ad_searches; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY ad_searches (id, created_at, updated_at, user_id, keyword, category_id, is_search_in_description, is_only_ads_with_images, is_notify_whenever_new_ads_posted, ip_id) FROM stdin;
\.


--
-- Name: ad_searches_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('ad_searches_id_seq', 1, false);


--
-- Data for Name: ad_views; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY ad_views (id, created_at, updated_at, user_id, ad_id, ip_id) FROM stdin;
\.


--
-- Name: ad_views_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('ad_views_id_seq', 1, false);


--
-- Data for Name: ads; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY ads (id, created_at, updated_at, user_id, title, slug, category_id, advertiser_type_id, is_an_exchange_item, price, is_negotiable, description, city_id, state_id, country_id, location, is_send_email_when_user_contact, latitude, longitude, hash, is_show_as_top_ads, advertiser_name, ad_in_top_end_date, phone_number, top_ads_end_date, is_show_ad_in_top, urgent_end_date, highlighted_end_date, is_removed_by_admin, is_urgent, is_highlighted, ad_view_count, ad_favorite_count, message_count, is_active, is_price_reduced, ad_start_date, ad_end_date, payment_gateway_id, pending_payment_log, paypal_pay_key, ad_report_count) FROM stdin;
\.


--
-- Name: ads_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('ads_id_seq', 1, false);


--
-- Data for Name: advertiser_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY advertiser_types (id, created_at, updated_at, name) FROM stdin;
1	2016-08-13 08:48:50	2016-08-13 08:48:50	Private
2	2016-08-13 08:48:59	2016-08-13 08:48:59	Professional
\.


--
-- Name: advertiser_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('advertiser_types_id_seq', 2, true);


--
-- Data for Name: attachments; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY attachments (id, created_at, updated_at, class, foreign_id, filename, dir, mimetype, filesize, height, width) FROM stdin;
\.


--
-- Name: attachments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('attachments_id_seq', 1, true);


--
-- Data for Name: banned_ips; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY banned_ips (id, created_at, updated_at, address, range, reason, redirect, thetime, timespan) FROM stdin;
\.


--
-- Name: banned_ips_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('banned_ips_id_seq', 1, false);


--
-- Data for Name: categories; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY categories (id, created_at, updated_at, name, slug, parent_id, allowed_free_ads_count, post_ad_fee, is_active, ad_count, form_field_count, ad_extra_day_count, description, is_popular, allowed_days_to_display_ad) FROM stdin;
\.


--
-- Name: categories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('categories_id_seq', 1, false);


--
-- Data for Name: cities; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY cities (id, created_at, updated_at, country_id, state_id, name, slug, city_code, is_active) FROM stdin;
\.


--
-- Name: cities_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('cities_id_seq', 1, true);


--
-- Data for Name: contacts; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY contacts (id, created_at, updated_at, first_name, last_name, email, phone, subject, message, ip_id) FROM stdin;
\.


--
-- Name: contacts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('contacts_id_seq', 1, true);


--
-- Data for Name: countries; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY countries (id, created_at, updated_at, iso_alpha2, iso_alpha3, iso_numeric, fips_code, name, capital, areainsqkm, population, continent, tld, currency, currencyname, phone, postalcodeformat, postalcoderegex, languages, geonameid, neighbours, equivalentfipscode) FROM stdin;
111	2016-11-22 10:18:50	2016-11-22 10:18:50	JM	JAM	388	JM	Jamaica	Kingston	10991	2847232	NA	.jm	JMD	Dollar	+1-876			en-JM	3489940		\\r
178	2016-11-22 10:18:50	2016-11-22 10:18:50	PL	POL	616	PL	Poland	Warsaw	312685	38500000	EU	.pl	PLN	Zloty	48	##-###	^(d{5})$	pl	798544	DE,LT,SK,CZ,BY,UA,RU	\\r
1	2016-11-22 10:18:50	2016-11-22 10:18:50	AF	AFG	4	AF	Afghanistan	Kabul	647500	29121286	AS	.af	AFN	Afghani	93			fa-AF,ps,uz-AF,tk	1149361	TM,CN,IR,TJ,PK,UZ	\\r
2	2016-11-22 10:18:50	2016-11-22 10:18:50	AX	ALA	248		Aland Islands	Mariehamn	0	26711	EU	.ax	EUR	Euro	+358-18			sv-AX	661882		FI\\r
3	2016-11-22 10:18:50	2016-11-22 10:18:50	AL	ALB	8	AL	Albania	Tirana	28748	2986952	EU	.al	ALL	Lek	355			sq,el	783754	MK,GR,CS,ME,RS,XK	\\r
4	2016-11-22 10:18:50	2016-11-22 10:18:50	DZ	DZA	12	AG	Algeria	Algiers	2381740	34586184	AF	.dz	DZD	Dinar	213	#####	^(d{5})$	ar-DZ	2589581	NE,EH,LY,MR,TN,MA,ML	\\r
5	2016-11-22 10:18:50	2016-11-22 10:18:50	AS	ASM	16	AQ	American Samoa	Pago Pago	199	57881	OC	.as	USD	Dollar	+1-684			en-AS,sm,to	5880801		\\r
6	2016-11-22 10:18:50	2016-11-22 10:18:50	AD	AND	20	AN	Andorra	Andorra la Vella	468	84000	EU	.ad	EUR	Euro	376	AD###	^(?:AD)*(d{3})$	ca	3041565	ES,FR	\\r
7	2016-11-22 10:18:50	2016-11-22 10:18:50	AO	AGO	24	AO	Angola	Luanda	1246700	13068161	AF	.ao	AOA	Kwanza	244			pt-AO	3351879	CD,NA,ZM,CG	\\r
8	2016-11-22 10:18:50	2016-11-22 10:18:50	AI	AIA	660	AV	Anguilla	The Valley	102	13254	NA	.ai	XCD	Dollar	+1-264			en-AI	3573511		\\r
9	2016-11-22 10:18:50	2016-11-22 10:18:50	AQ	ATA	10	AY	Antarctica		14000000	0	AN	.aq							6697173		\\r
10	2016-11-22 10:18:50	2016-11-22 10:18:50	AG	ATG	28	AC	Antigua and Barbuda	St. Johns	443	86754	NA	.ag	XCD	Dollar	+1-268			en-AG	3576396		\\r
11	2016-11-22 10:18:50	2016-11-22 10:18:50	AR	ARG	32	AR	Argentina	Buenos Aires	2766890	41343201	SA	.ar	ARS	Peso	54	@####@@@	^([A-Z]d{4}[A-Z]{3})	es-AR,en,it,de,fr,gn	3865483	CL,BO,UY,PY,BR	\\r
12	2016-11-22 10:18:50	2016-11-22 10:18:50	AM	ARM	51	AM	Armenia	Yerevan	29800	2968000	AS	.am	AMD	Dram	374	######	^(d{6})$	hy	174982	GE,IR,AZ,TR	\\r
13	2016-11-22 10:18:50	2016-11-22 10:18:50	AW	ABW	533	AA	Aruba	Oranjestad	193	71566	NA	.aw	AWG	Guilder	297			nl-AW,es,en	3577279		\\r
14	2016-11-22 10:18:50	2016-11-22 10:18:50	AU	AUS	36	AS	Australia	Canberra	7686850	21515754	OC	.au	AUD	Dollar	61	####	^(d{4})$	en-AU	2077456		\\r
15	2016-11-22 10:18:50	2016-11-22 10:18:50	AT	AUT	40	AU	Austria	Vienna	83858	8205000	EU	.at	EUR	Euro	43	####	^(d{4})$	de-AT,hr,hu,sl	2782113	CH,DE,HU,SK,CZ,IT,SI	\\r
16	2016-11-22 10:18:50	2016-11-22 10:18:50	AZ	AZE	31	AJ	Azerbaijan	Baku	86600	8303512	AS	.az	AZN	Manat	994	AZ ####	^(?:AZ)*(d{4})$	az,ru,hy	587116	GE,IR,AM,TR,RU	\\r
17	2016-11-22 10:18:50	2016-11-22 10:18:50	BS	BHS	44	BF	Bahamas	Nassau	13940	301790	NA	.bs	BSD	Dollar	+1-242			en-BS	3572887		\\r
18	2016-11-22 10:18:50	2016-11-22 10:18:50	BH	BHR	48	BA	Bahrain	Manama	665	738004	AS	.bh	BHD	Dinar	973	####|###	^(d{3}d?)$	ar-BH,en,fa,ur	290291		\\r
19	2016-11-22 10:18:50	2016-11-22 10:18:50	BD	BGD	50	BG	Bangladesh	Dhaka	144000	156118464	AS	.bd	BDT	Taka	880	####	^(d{4})$	bn-BD,en	1210997	MM,IN	\\r
20	2016-11-22 10:18:50	2016-11-22 10:18:50	BB	BRB	52	BB	Barbados	Bridgetown	431	285653	NA	.bb	BBD	Dollar	+1-246	BB#####	^(?:BB)*(d{5})$	en-BB	3374084		\\r
21	2016-11-22 10:18:50	2016-11-22 10:18:50	BY	BLR	112	BO	Belarus	Minsk	207600	9685000	EU	.by	BYR	Ruble	375	######	^(d{6})$	be,ru	630336	PL,LT,UA,RU,LV	\\r
22	2016-11-22 10:18:50	2016-11-22 10:18:50	BE	BEL	56	BE	Belgium	Brussels	30510	10403000	EU	.be	EUR	Euro	32	####	^(d{4})$	nl-BE,fr-BE,de-BE	2802361	DE,NL,LU,FR	
23	2016-11-22 10:18:50	2016-11-22 10:18:50	BZ	BLZ	84	BH	Belize	Belmopan	22966	314522	NA	.bz	BZD	Dollar	501			en-BZ,es	3582678	GT,MX	\\r
24	2016-11-22 10:18:50	2016-11-22 10:18:50	BJ	BEN	204	BN	Benin	Porto-Novo	112620	9056010	AF	.bj	XOF	Franc	229			fr-BJ	2395170	NE,TG,BF,NG	\\r
25	2016-11-22 10:18:50	2016-11-22 10:18:50	BM	BMU	60	BD	Bermuda	Hamilton	53	65365	NA	.bm	BMD	Dollar	+1-441	@@ ##	^([A-Z]{2}d{2})$	en-BM,pt	3573345		\\r
26	2016-11-22 10:18:50	2016-11-22 10:18:50	BT	BTN	64	BT	Bhutan	Thimphu	47000	699847	AS	.bt	BTN	Ngultrum	975			dz	1252634	CN,IN	\\r
27	2016-11-22 10:18:50	2016-11-22 10:18:50	BO	BOL	68	BL	Bolivia	Sucre	1098580	9947418	SA	.bo	BOB	Boliviano	591			es-BO,qu,ay	3923057	PE,CL,PY,BR,AR	\\r
28	2016-11-22 10:18:50	2016-11-22 10:18:50	BQ	BES	535		Bonaire, Saint Eustatius and Saba 		0	18012	NA	.bq	USD	Dollar	599			nl,pap,en	7626844		\\r
29	2016-11-22 10:18:50	2016-11-22 10:18:50	BA	BIH	70	BK	Bosnia and Herzegovina	Sarajevo	51129	4590000	EU	.ba	BAM	Marka	387	#####	^(d{5})$	bs,hr-BA,sr-BA	3277605	CS,HR,ME,RS	\\r
30	2016-11-22 10:18:50	2016-11-22 10:18:50	BW	BWA	72	BC	Botswana	Gaborone	600370	2029307	AF	.bw	BWP	Pula	267			en-BW,tn-BW	933860	ZW,ZA,NA	\\r
31	2016-11-22 10:18:50	2016-11-22 10:18:50	BV	BVT	74	BV	Bouvet Island		0	0	AN	.bv	NOK	Krone					3371123		\\r
32	2016-11-22 10:18:50	2016-11-22 10:18:50	BR	BRA	76	BR	Brazil	Brasilia	8511965	201103330	SA	.br	BRL	Real	55	#####-###	^(d{8})$	pt-BR,es,en,fr	3469034	SR,PE,BO,UY,GY,PY,GF	\\r
33	2016-11-22 10:18:50	2016-11-22 10:18:50	IO	IOT	86	IO	British Indian Ocean Territory	Diego Garcia	60	4000	AS	.io	USD	Dollar	246			en-IO	1282588		\\r
34	2016-11-22 10:18:50	2016-11-22 10:18:50	VG	VGB	92	VI	British Virgin Islands	Road Town	153	21730	NA	.vg	USD	Dollar	+1-284			en-VG	3577718		\\r
35	2016-11-22 10:18:50	2016-11-22 10:18:50	BN	BRN	96	BX	Brunei	Bandar Seri Begawan	5770	395027	AS	.bn	BND	Dollar	673	@@####	^([A-Z]{2}d{4})$	ms-BN,en-BN	1820814	MY	\\r
36	2016-11-22 10:18:50	2016-11-22 10:18:50	BG	BGR	100	BU	Bulgaria	Sofia	110910	7148785	EU	.bg	BGN	Lev	359	####	^(d{4})$	bg,tr-BG	732800	MK,GR,RO,CS,TR,RS	\\r
37	2016-11-22 10:18:50	2016-11-22 10:18:50	BF	BFA	854	UV	Burkina Faso	Ouagadougou	274200	16241811	AF	.bf	XOF	Franc	226			fr-BF	2361809	NE,BJ,GH,CI,TG,ML	\\r
38	2016-11-22 10:18:50	2016-11-22 10:18:50	BI	BDI	108	BY	Burundi	Bujumbura	27830	9863117	AF	.bi	BIF	Franc	257			fr-BI,rn	433561	TZ,CD,RW	\\r
39	2016-11-22 10:18:50	2016-11-22 10:18:50	KH	KHM	116	CB	Cambodia	Phnom Penh	181040	14453680	AS	.kh	KHR	Riels	855	#####	^(d{5})$	km,fr,en	1831722	LA,TH,VN	\\r
40	2016-11-22 10:18:50	2016-11-22 10:18:50	CM	CMR	120	CM	Cameroon	Yaounde	475440	19294149	AF	.cm	XAF	Franc	237			en-CM,fr-CM	2233387	TD,CF,GA,GQ,CG,NG	\\r
41	2016-11-22 10:18:50	2016-11-22 10:18:50	CA	CAN	124	CA	Canada	Ottawa	9984670	33679000	NA	.ca	CAD	Dollar	1	@#@ #@#	^([a-zA-Z]d[a-zA-Z]d	en-CA,fr-CA,iu	6251999	US	\\r
42	2016-11-22 10:18:50	2016-11-22 10:18:50	CV	CPV	132	CV	Cape Verde	Praia	4033	508659	AF	.cv	CVE	Escudo	238	####	^(d{4})$	pt-CV	3374766		\\r
43	2016-11-22 10:18:50	2016-11-22 10:18:50	KY	CYM	136	CJ	Cayman Islands	George Town	262	44270	NA	.ky	KYD	Dollar	+1-345			en-KY	3580718		\\r
44	2016-11-22 10:18:50	2016-11-22 10:18:50	CF	CAF	140	CT	Central African Republic	Bangui	622984	4844927	AF	.cf	XAF	Franc	236			fr-CF,sg,ln,kg	239880	TD,SD,CD,SS,CM,CG	\\r
45	2016-11-22 10:18:50	2016-11-22 10:18:50	TD	TCD	148	CD	Chad	NDjamena	1284000	10543464	AF	.td	XAF	Franc	235			fr-TD,ar-TD,sre	2434508	NE,LY,CF,SD,CM,NG	\\r
46	2016-11-22 10:18:50	2016-11-22 10:18:50	CL	CHL	152	CI	Chile	Santiago	756950	16746491	SA	.cl	CLP	Peso	56	#######	^(d{7})$	es-CL	3895114	PE,BO,AR	\\r
47	2016-11-22 10:18:50	2016-11-22 10:18:50	CN	CHN	156	CH	China	Beijing	9596960	1330044000	AS	.cn	CNY	Yuan Renminbi	86	######	^(d{6})$	zh-CN,yue,wuu,dta,ug,za	1814991	LA,BT,TJ,KZ,MN,AF,NP	\\r
48	2016-11-22 10:18:50	2016-11-22 10:18:50	CX	CXR	162	KT	Christmas Island	Flying Fish Cove	135	1500	AS	.cx	AUD	Dollar	61	####	^(d{4})$	en,zh,ms-CC	2078138		\\r
49	2016-11-22 10:18:50	2016-11-22 10:18:50	CC	CCK	166	CK	Cocos Islands	West Island	14	628	AS	.cc	AUD	Dollar	61			ms-CC,en	1547376		\\r
50	2016-11-22 10:18:50	2016-11-22 10:18:50	CO	COL	170	CO	Colombia	Bogota	1138910	44205293	SA	.co	COP	Peso	57			es-CO	3686110	EC,PE,PA,BR,VE	\\r
51	2016-11-22 10:18:50	2016-11-22 10:18:50	KM	COM	174	CN	Comoros	Moroni	2170	773407	AF	.km	KMF	Franc	269			ar,fr-KM	921929		\\r
52	2016-11-22 10:18:50	2016-11-22 10:18:50	CK	COK	184	CW	Cook Islands	Avarua	240	21388	OC	.ck	NZD	Dollar	682			en-CK,mi	1899402		\\r
53	2016-11-22 10:18:50	2016-11-22 10:18:50	CR	CRI	188	CS	Costa Rica	San Jose	51100	4516220	NA	.cr	CRC	Colon	506	####	^(d{4})$	es-CR,en	3624060	PA,NI	\\r
54	2016-11-22 10:18:50	2016-11-22 10:18:50	HR	HRV	191	HR	Croatia	Zagreb	56542	4491000	EU	.hr	HRK	Kuna	385	HR-#####	^(?:HR)*(d{5})$	hr-HR,sr	3202326	HU,SI,CS,BA,ME,RS	\\r
55	2016-11-22 10:18:50	2016-11-22 10:18:50	CU	CUB	192	CU	Cuba	Havana	110860	11423000	NA	.cu	CUP	Peso	53	CP #####	^(?:CP)*(d{5})$	es-CU	3562981	US	\\r
56	2016-11-22 10:18:50	2016-11-22 10:18:50	CW	CUW	531	UC	Curacao	 Willemstad	0	141766	NA	.cw	ANG	Guilder	599			nl,pap	7626836		\\r
57	2016-11-22 10:18:50	2016-11-22 10:18:50	CY	CYP	196	CY	Cyprus	Nicosia	9250	1102677	EU	.cy	EUR	Euro	357	####	^(d{4})$	el-CY,tr-CY,en	146669		\\r
58	2016-11-22 10:18:50	2016-11-22 10:18:50	CZ	CZE	203	EZ	Czech Republic	Prague	78866	10476000	EU	.cz	CZK	Koruna	420	### ##	^(d{5})$	cs,sk	3077311	PL,DE,SK,AT	\\r
59	2016-11-22 10:18:50	2016-11-22 10:18:50	CD	COD	180	CG	Democratic Republic of the Congo	Kinshasa	2345410	70916439	AF	.cd	CDF	Franc	243			fr-CD,ln,kg	203312	TZ,CF,SS,RW,ZM,BI,UG	\\r
60	2016-11-22 10:18:50	2016-11-22 10:18:50	DK	DNK	208	DA	Denmark	Copenhagen	43094	5484000	EU	.dk	DKK	Krone	45	####	^(d{4})$	da-DK,en,fo,de-DK	2623032	DE	\\r
61	2016-11-22 10:18:50	2016-11-22 10:18:50	DJ	DJI	262	DJ	Djibouti	Djibouti	23000	740528	AF	.dj	DJF	Franc	253			fr-DJ,ar,so-DJ,aa	223816	ER,ET,SO	\\r
62	2016-11-22 10:18:50	2016-11-22 10:18:50	DM	DMA	212	DO	Dominica	Roseau	754	72813	NA	.dm	XCD	Dollar	+1-767			en-DM	3575830		\\r
63	2016-11-22 10:18:50	2016-11-22 10:18:50	DO	DOM	214	DR	Dominican Republic	Santo Domingo	48730	9823821	NA	.do	DOP	Peso	+1-809 and	#####	^(d{5})$	es-DO	3508796	HT	\\r
64	2016-11-22 10:18:50	2016-11-22 10:18:50	TL	TLS	626	TT	East Timor	Dili	15007	1154625	OC	.tl	USD	Dollar	670			tet,pt-TL,id,en	1966436	ID	\\r
65	2016-11-22 10:18:50	2016-11-22 10:18:50	EC	ECU	218	EC	Ecuador	Quito	283560	14790608	SA	.ec	USD	Dollar	593	@####@	^([a-zA-Z]d{4}[a-zA-	es-EC	3658394	PE,CO	\\r
66	2016-11-22 10:18:50	2016-11-22 10:18:50	EG	EGY	818	EG	Egypt	Cairo	1001450	80471869	AF	.eg	EGP	Pound	20	#####	^(d{5})$	ar-EG,en,fr	357994	LY,SD,IL	\\r
67	2016-11-22 10:18:50	2016-11-22 10:18:50	SV	SLV	222	ES	El Salvador	San Salvador	21040	6052064	NA	.sv	USD	Dollar	503	CP ####	^(?:CP)*(d{4})$	es-SV	3585968	GT,HN	\\r
68	2016-11-22 10:18:50	2016-11-22 10:18:50	GQ	GNQ	226	EK	Equatorial Guinea	Malabo	28051	1014999	AF	.gq	XAF	Franc	240			es-GQ,fr	2309096	GA,CM	\\r
69	2016-11-22 10:18:50	2016-11-22 10:18:50	ER	ERI	232	ER	Eritrea	Asmara	121320	5792984	AF	.er	ERN	Nakfa	291			aa-ER,ar,tig,kun,ti-ER	338010	ET,SD,DJ	\\r
70	2016-11-22 10:18:50	2016-11-22 10:18:50	EE	EST	233	EN	Estonia	Tallinn	45226	1291170	EU	.ee	EUR	Euro	372	#####	^(d{5})$	et,ru	453733	RU,LV	\\r
71	2016-11-22 10:18:50	2016-11-22 10:18:50	ET	ETH	231	ET	Ethiopia	Addis Ababa	1127127	88013491	AF	.et	ETB	Birr	251	####	^(d{4})$	am,en-ET,om-ET,ti-ET,so-ET,sid	337996	ER,KE,SD,SS,SO,DJ	\\r
72	2016-11-22 10:18:50	2016-11-22 10:18:50	FK	FLK	238	FK	Falkland Islands	Stanley	12173	2638	SA	.fk	FKP	Pound	500			en-FK	3474414		\\r
73	2016-11-22 10:18:50	2016-11-22 10:18:50	FO	FRO	234	FO	Faroe Islands	Torshavn	1399	48228	EU	.fo	DKK	Krone	298	FO-###	^(?:FO)*(d{3})$	fo,da-FO	2622320		\\r
74	2016-11-22 10:18:50	2016-11-22 10:18:50	FJ	FJI	242	FJ	Fiji	Suva	18270	875983	OC	.fj	FJD	Dollar	679			en-FJ,fj	2205218		\\r
75	2016-11-22 10:18:50	2016-11-22 10:18:50	FI	FIN	246	FI	Finland	Helsinki	337030	5244000	EU	.fi	EUR	Euro	358	#####	^(?:FI)*(d{5})$	fi-FI,sv-FI,smn	660013	NO,RU,SE	\\r
76	2016-11-22 10:18:50	2016-11-22 10:18:50	FR	FRA	250	FR	France	Paris	547030	64768389	EU	.fr	EUR	Euro	33	#####	^(d{5})$	fr-FR,frp,br,co,ca,eu,oc	3017382	CH,DE,BE,LU,IT,AD,MC	\\r
77	2016-11-22 10:18:50	2016-11-22 10:18:50	GF	GUF	254	FG	French Guiana	Cayenne	91000	195506	SA	.gf	EUR	Euro	594	#####	^((97)|(98)3d{2})$	fr-GF	3381670	SR,BR	\\r
78	2016-11-22 10:18:50	2016-11-22 10:18:50	PF	PYF	258	FP	French Polynesia	Papeete	4167	270485	OC	.pf	XPF	Franc	689	#####	^((97)|(98)7d{2})$	fr-PF,ty	4030656		\\r
79	2016-11-22 10:18:50	2016-11-22 10:18:50	TF	ATF	260	FS	French Southern Territories	Port-aux-Francais	7829	140	AN	.tf	EUR	Euro				fr	1546748		\\r
80	2016-11-22 10:18:50	2016-11-22 10:18:50	GA	GAB	266	GB	Gabon	Libreville	267667	1545255	AF	.ga	XAF	Franc	241			fr-GA	2400553	CM,GQ,CG	\\r
81	2016-11-22 10:18:50	2016-11-22 10:18:50	GM	GMB	270	GA	Gambia	Banjul	11300	1593256	AF	.gm	GMD	Dalasi	220			en-GM,mnk,wof,wo,ff	2413451	SN	\\r
82	2016-11-22 10:18:50	2016-11-22 10:18:50	GE	GEO	268	GG	Georgia	Tbilisi	69700	4630000	AS	.ge	GEL	Lari	995	####	^(d{4})$	ka,ru,hy,az	614540	AM,AZ,TR,RU	\\r
83	2016-11-22 10:18:50	2016-11-22 10:18:50	DE	DEU	276	GM	Germany	Berlin	357021	81802257	EU	.de	EUR	Euro	49	#####	^(d{5})$	de	2921044	CH,PL,NL,DK,BE,CZ,LU	\\r
84	2016-11-22 10:18:50	2016-11-22 10:18:50	GH	GHA	288	GH	Ghana	Accra	239460	24339838	AF	.gh	GHS	Cedi	233			en-GH,ak,ee,tw	2300660	CI,TG,BF	\\r
85	2016-11-22 10:18:50	2016-11-22 10:18:50	GI	GIB	292	GI	Gibraltar	Gibraltar	6.5	27884	EU	.gi	GIP	Pound	350			en-GI,es,it,pt	2411586	ES	\\r
86	2016-11-22 10:18:50	2016-11-22 10:18:50	GR	GRC	300	GR	Greece	Athens	131940	11000000	EU	.gr	EUR	Euro	30	### ##	^(d{5})$	el-GR,en,fr	390903	AL,MK,TR,BG	\\r
87	2016-11-22 10:18:50	2016-11-22 10:18:50	GL	GRL	304	GL	Greenland	Nuuk	2166086	56375	NA	.gl	DKK	Krone	299	####	^(d{4})$	kl,da-GL,en	3425505		\\r
88	2016-11-22 10:18:50	2016-11-22 10:18:50	GD	GRD	308	GJ	Grenada	St. Georges	344	107818	NA	.gd	XCD	Dollar	+1-473			en-GD	3580239		\\r
89	2016-11-22 10:18:50	2016-11-22 10:18:50	GP	GLP	312	GP	Guadeloupe	Basse-Terre	1780	443000	NA	.gp	EUR	Euro	590	#####	^((97)|(98)d{3})$	fr-GP	3579143	AN	\\r
90	2016-11-22 10:18:50	2016-11-22 10:18:50	GU	GUM	316	GQ	Guam	Hagatna	549	159358	OC	.gu	USD	Dollar	+1-671	969##	^(969d{2})$	en-GU,ch-GU	4043988		\\r
91	2016-11-22 10:18:50	2016-11-22 10:18:50	GT	GTM	320	GT	Guatemala	Guatemala City	108890	13550440	NA	.gt	GTQ	Quetzal	502	#####	^(d{5})$	es-GT	3595528	MX,HN,BZ,SV	\\r
92	2016-11-22 10:18:50	2016-11-22 10:18:50	GG	GGY	831	GK	Guernsey	St Peter Port	78	65228	EU	.gg	GBP	Pound	+44-1481	@# #@@|@## #@@|@@# #	^(([A-Z]d{2}[A-Z]{2}	en,fr	3042362		\\r
93	2016-11-22 10:18:50	2016-11-22 10:18:50	GN	GIN	324	GV	Guinea	Conakry	245857	10324025	AF	.gn	GNF	Franc	224			fr-GN	2420477	LR,SN,SL,CI,GW,ML	\\r
94	2016-11-22 10:18:50	2016-11-22 10:18:50	GW	GNB	624	PU	Guinea-Bissau	Bissau	36120	1565126	AF	.gw	XOF	Franc	245	####	^(d{4})$	pt-GW,pov	2372248	SN,GN	\\r
95	2016-11-22 10:18:50	2016-11-22 10:18:50	GY	GUY	328	GY	Guyana	Georgetown	214970	748486	SA	.gy	GYD	Dollar	592			en-GY	3378535	SR,BR,VE	\\r
96	2016-11-22 10:18:50	2016-11-22 10:18:50	HT	HTI	332	HA	Haiti	Port-au-Prince	27750	9648924	NA	.ht	HTG	Gourde	509	HT####	^(?:HT)*(d{4})$	ht,fr-HT	3723988	DO	\\r
97	2016-11-22 10:18:50	2016-11-22 10:18:50	HM	HMD	334	HM	Heard Island and McDonald Islands		412	0	AN	.hm	AUD	Dollar					1547314		\\r
98	2016-11-22 10:18:50	2016-11-22 10:18:50	HN	HND	340	HO	Honduras	Tegucigalpa	112090	7989415	NA	.hn	HNL	Lempira	504	@@####	^([A-Z]{2}d{4})$	es-HN	3608932	GT,NI,SV	\\r
99	2016-11-22 10:18:50	2016-11-22 10:18:50	HK	HKG	344	HK	Hong Kong	Hong Kong	1092	6898686	AS	.hk	HKD	Dollar	852			zh-HK,yue,zh,en	1819730		\\r
100	2016-11-22 10:18:50	2016-11-22 10:18:50	HU	HUN	348	HU	Hungary	Budapest	93030	9930000	EU	.hu	HUF	Forint	36	####	^(d{4})$	hu-HU	719819	SK,SI,RO,UA,CS,HR,AT	\\r
101	2016-11-22 10:18:50	2016-11-22 10:18:50	IS	ISL	352	IC	Iceland	Reykjavik	103000	308910	EU	.is	ISK	Krona	354	###	^(d{3})$	is,en,de,da,sv,no	2629691		\\r
153	2016-11-22 10:18:50	2016-11-22 10:18:50	NA	NAM	516	WA	Namibia	Windhoek	825418	2128471	AF	.na	NAD	Dollar	264			en-NA,af,de,hz,naq	3355338	ZA,BW,ZM,AO	\\r
102	2016-11-22 10:18:50	2016-11-22 10:18:50	IN	IND	356	IN	India	New Delhi	3287590	1173108018	AS	.in	INR	Rupee	91	######	^(d{6})$	en-IN,hi,bn,te,mr,ta,ur,gu,kn,ml,or,pa,as,bh,sat,ks,ne,sd,kok,doi,mni,sit,sa,fr,lus,inc	1269750	CN,NP,MM,BT,PK,BD	\\r
103	2016-11-22 10:18:50	2016-11-22 10:18:50	ID	IDN	360	ID	Indonesia	Jakarta	1919440	242968342	AS	.id	IDR	Rupiah	62	#####	^(d{5})$	id,en,nl,jv	1643084	PG,TL,MY	\\r
104	2016-11-22 10:18:50	2016-11-22 10:18:50	IR	IRN	364	IR	Iran	Tehran	1648000	76923300	AS	.ir	IRR	Rial	98	##########	^(d{10})$	fa-IR,ku	130758	TM,AF,IQ,AM,PK,AZ,TR	\\r
105	2016-11-22 10:18:50	2016-11-22 10:18:50	IQ	IRQ	368	IZ	Iraq	Baghdad	437072	29671605	AS	.iq	IQD	Dinar	964	#####	^(d{5})$	ar-IQ,ku,hy	99237	SY,SA,IR,JO,TR,KW	\\r
106	2016-11-22 10:18:50	2016-11-22 10:18:50	IE	IRL	372	EI	Ireland	Dublin	70280	4622917	EU	.ie	EUR	Euro	353			en-IE,ga-IE	2963597	GB	\\r
143	2016-11-22 10:18:50	2016-11-22 10:18:50	MX	MEX	484	MX	Mexico	Mexico City	1972550	112468855	NA	.mx	MXN	Peso	52	#####	^(d{5})$	es-MX	3996063	GT,US,BZ	\\r
107	2016-11-22 10:18:50	2016-11-22 10:18:50	IM	IMN	833	IM	Isle of Man	Douglas, Isle of Man	572	75049	EU	.im	GBP	Pound	+44-1624	@# #@@|@## #@@|@@# #	^(([A-Z]d{2}[A-Z]{2}	en,gv	3042225		\\r
108	2016-11-22 10:18:50	2016-11-22 10:18:50	IL	ISR	376	IS	Israel	Jerusalem	20770	7353985	AS	.il	ILS	Shekel	972	#####	^(d{5})$	he,ar-IL,en-IL,	294640	SY,JO,LB,EG,PS	\\r
109	2016-11-22 10:18:50	2016-11-22 10:18:50	IT	ITA	380	IT	Italy	Rome	301230	60340328	EU	.it	EUR	Euro	39	#####	^(d{5})$	it-IT,de-IT,fr-IT,sc,ca,co,sl	3175395	CH,VA,SI,SM,FR,AT	\\r
110	2016-11-22 10:18:50	2016-11-22 10:18:50	CI	CIV	384	IV	Ivory Coast	Yamoussoukro	322460	21058798	AF	.ci	XOF	Franc	225			fr-CI	2287781	LR,GH,GN,BF,ML	\\r
112	2016-11-22 10:18:50	2016-11-22 10:18:50	JP	JPN	392	JA	Japan	Tokyo	377835	127288000	AS	.jp	JPY	Yen	81	###-####	^(d{7})$	ja	1861060		\\r
113	2016-11-22 10:18:50	2016-11-22 10:18:50	JE	JEY	832	JE	Jersey	Saint Helier	116	90812	EU	.je	GBP	Pound	+44-1534	@# #@@|@## #@@|@@# #	^(([A-Z]d{2}[A-Z]{2}	en,pt	3042142		\\r
114	2016-11-22 10:18:50	2016-11-22 10:18:50	JO	JOR	400	JO	Jordan	Amman	92300	6407085	AS	.jo	JOD	Dinar	962	#####	^(d{5})$	ar-JO,en	248816	SY,SA,IQ,IL,PS	\\r
115	2016-11-22 10:18:50	2016-11-22 10:18:50	KZ	KAZ	398	KZ	Kazakhstan	Astana	2717300	15340000	AS	.kz	KZT	Tenge	7	######	^(d{6})$	kk,ru	1522867	TM,CN,KG,UZ,RU	\\r
116	2016-11-22 10:18:50	2016-11-22 10:18:50	KE	KEN	404	KE	Kenya	Nairobi	582650	40046566	AF	.ke	KES	Shilling	254	#####	^(d{5})$	en-KE,sw-KE	192950	ET,TZ,SS,SO,UG	\\r
117	2016-11-22 10:18:50	2016-11-22 10:18:50	KI	KIR	296	KR	Kiribati	Tarawa	811	92533	OC	.ki	AUD	Dollar	686			en-KI,gil	4030945		\\r
118	2016-11-22 10:18:50	2016-11-22 10:18:50	XK	XKX	0	KV	Kosovo	Pristina	0	1800000	EU		EUR	Euro				sq,sr	831053	RS,AL,MK,ME	\\r
119	2016-11-22 10:18:50	2016-11-22 10:18:50	KW	KWT	414	KU	Kuwait	Kuwait City	17820	2789132	AS	.kw	KWD	Dinar	965	#####	^(d{5})$	ar-KW,en	285570	SA,IQ	\\r
120	2016-11-22 10:18:50	2016-11-22 10:18:50	KG	KGZ	417	KG	Kyrgyzstan	Bishkek	198500	5508626	AS	.kg	KGS	Som	996	######	^(d{6})$	ky,uz,ru	1527747	CN,TJ,UZ,KZ	\\r
121	2016-11-22 10:18:50	2016-11-22 10:18:50	LA	LAO	418	LA	Laos	Vientiane	236800	6368162	AS	.la	LAK	Kip	856	#####	^(d{5})$	lo,fr,en	1655842	CN,MM,KH,TH,VN	\\r
122	2016-11-22 10:18:50	2016-11-22 10:18:50	LV	LVA	428	LG	Latvia	Riga	64589	2217969	EU	.lv	LVL	Lat	371	LV-####	^(?:LV)*(d{4})$	lv,ru,lt	458258	LT,EE,BY,RU	\\r
123	2016-11-22 10:18:50	2016-11-22 10:18:50	LB	LBN	422	LE	Lebanon	Beirut	10400	4125247	AS	.lb	LBP	Pound	961	#### ####|####	^(d{4}(d{4})?)$	ar-LB,fr-LB,en,hy	272103	SY,IL	\\r
124	2016-11-22 10:18:50	2016-11-22 10:18:50	LS	LSO	426	LT	Lesotho	Maseru	30355	1919552	AF	.ls	LSL	Loti	266	###	^(d{3})$	en-LS,st,zu,xh	932692	ZA	\\r
125	2016-11-22 10:18:50	2016-11-22 10:18:50	LR	LBR	430	LI	Liberia	Monrovia	111370	3685076	AF	.lr	LRD	Dollar	231	####	^(d{4})$	en-LR	2275384	SL,CI,GN	\\r
126	2016-11-22 10:18:50	2016-11-22 10:18:50	LY	LBY	434	LY	Libya	Tripolis	1759540	6461454	AF	.ly	LYD	Dinar	218			ar-LY,it,en	2215636	TD,NE,DZ,SD,TN,EG	\\r
127	2016-11-22 10:18:50	2016-11-22 10:18:50	LI	LIE	438	LS	Liechtenstein	Vaduz	160	35000	EU	.li	CHF	Franc	423	####	^(d{4})$	de-LI	3042058	CH,AT	\\r
128	2016-11-22 10:18:50	2016-11-22 10:18:50	LT	LTU	440	LH	Lithuania	Vilnius	65200	3565000	EU	.lt	LTL	Litas	370	LT-#####	^(?:LT)*(d{5})$	lt,ru,pl	597427	PL,BY,RU,LV	\\r
129	2016-11-22 10:18:50	2016-11-22 10:18:50	LU	LUX	442	LU	Luxembourg	Luxembourg	2586	497538	EU	.lu	EUR	Euro	352	####	^(d{4})$	lb,de-LU,fr-LU	2960313	DE,BE,FR	\\r
130	2016-11-22 10:18:50	2016-11-22 10:18:50	MO	MAC	446	MC	Macao	Macao	254	449198	AS	.mo	MOP	Pataca	853			zh,zh-MO,pt	1821275		\\r
131	2016-11-22 10:18:50	2016-11-22 10:18:50	MK	MKD	807	MK	Macedonia	Skopje	25333	2061000	EU	.mk	MKD	Denar	389	####	^(d{4})$	mk,sq,tr,rmm,sr	718075	AL,GR,CS,BG,RS,XK	\\r
132	2016-11-22 10:18:50	2016-11-22 10:18:50	MG	MDG	450	MA	Madagascar	Antananarivo	587040	21281844	AF	.mg	MGA	Ariary	261	###	^(d{3})$	fr-MG,mg	1062947		\\r
133	2016-11-22 10:18:50	2016-11-22 10:18:50	MW	MWI	454	MI	Malawi	Lilongwe	118480	15447500	AF	.mw	MWK	Kwacha	265			ny,yao,tum,swk	927384	TZ,MZ,ZM	\\r
134	2016-11-22 10:18:50	2016-11-22 10:18:50	MY	MYS	458	MY	Malaysia	Kuala Lumpur	329750	28274729	AS	.my	MYR	Ringgit	60	#####	^(d{5})$	ms-MY,en,zh,ta,te,ml,pa,th	1733045	BN,TH,ID	\\r
135	2016-11-22 10:18:50	2016-11-22 10:18:50	MV	MDV	462	MV	Maldives	Male	300	395650	AS	.mv	MVR	Rufiyaa	960	#####	^(d{5})$	dv,en	1282028		\\r
136	2016-11-22 10:18:50	2016-11-22 10:18:50	ML	MLI	466	ML	Mali	Bamako	1240000	13796354	AF	.ml	XOF	Franc	223			fr-ML,bm	2453866	SN,NE,DZ,CI,GN,MR,BF	\\r
137	2016-11-22 10:18:50	2016-11-22 10:18:50	MT	MLT	470	MT	Malta	Valletta	316	403000	EU	.mt	EUR	Euro	356	@@@ ###|@@@ ##	^([A-Z]{3}d{2}d?)$	mt,en-MT	2562770		\\r
138	2016-11-22 10:18:50	2016-11-22 10:18:50	MH	MHL	584	RM	Marshall Islands	Majuro	181.300000000000011	65859	OC	.mh	USD	Dollar	692			mh,en-MH	2080185		\\r
139	2016-11-22 10:18:50	2016-11-22 10:18:50	MQ	MTQ	474	MB	Martinique	Fort-de-France	1100	432900	NA	.mq	EUR	Euro	596	#####	^(d{5})$	fr-MQ	3570311		\\r
140	2016-11-22 10:18:50	2016-11-22 10:18:50	MR	MRT	478	MR	Mauritania	Nouakchott	1030700	3205060	AF	.mr	MRO	Ouguiya	222			ar-MR,fuc,snk,fr,mey,wo	2378080	SN,DZ,EH,ML	\\r
141	2016-11-22 10:18:50	2016-11-22 10:18:50	MU	MUS	480	MP	Mauritius	Port Louis	2040	1294104	AF	.mu	MUR	Rupee	230			en-MU,bho,fr	934292		\\r
142	2016-11-22 10:18:50	2016-11-22 10:18:50	YT	MYT	175	MF	Mayotte	Mamoudzou	374	159042	AF	.yt	EUR	Euro	262	#####	^(d{5})$	fr-YT	1024031		\\r
144	2016-11-22 10:18:50	2016-11-22 10:18:50	FM	FSM	583	FM	Micronesia	Palikir	702	107708	OC	.fm	USD	Dollar	691	#####	^(d{5})$	en-FM,chk,pon,yap,kos,uli,woe,nkr,kpg	2081918		\\r
145	2016-11-22 10:18:50	2016-11-22 10:18:50	MD	MDA	498	MD	Moldova	Chisinau	33843	4324000	EU	.md	MDL	Leu	373	MD-####	^(?:MD)*(d{4})$	ro,ru,gag,tr	617790	RO,UA	\\r
146	2016-11-22 10:18:50	2016-11-22 10:18:50	MC	MCO	492	MN	Monaco	Monaco	1.94999999999999996	32965	EU	.mc	EUR	Euro	377	#####	^(d{5})$	fr-MC,en,it	2993457	FR	\\r
147	2016-11-22 10:18:50	2016-11-22 10:18:50	MN	MNG	496	MG	Mongolia	Ulan Bator	1565000	3086918	AS	.mn	MNT	Tugrik	976	######	^(d{6})$	mn,ru	2029969	CN,RU	\\r
148	2016-11-22 10:18:50	2016-11-22 10:18:50	ME	MNE	499	MJ	Montenegro	Podgorica	14026	666730	EU	.me	EUR	Euro	382	#####	^(d{5})$	sr,hu,bs,sq,hr,rom	3194884	AL,HR,BA,RS,XK	\\r
149	2016-11-22 10:18:50	2016-11-22 10:18:50	MS	MSR	500	MH	Montserrat	Plymouth	102	9341	NA	.ms	XCD	Dollar	+1-664			en-MS	3578097		\\r
150	2016-11-22 10:18:50	2016-11-22 10:18:50	MA	MAR	504	MO	Morocco	Rabat	446550	31627428	AF	.ma	MAD	Dirham	212	#####	^(d{5})$	ar-MA,fr	2542007	DZ,EH,ES	\\r
151	2016-11-22 10:18:50	2016-11-22 10:18:50	MZ	MOZ	508	MZ	Mozambique	Maputo	801590	22061451	AF	.mz	MZN	Metical	258	####	^(d{4})$	pt-MZ,vmw	1036973	ZW,TZ,SZ,ZA,ZM,MW	\\r
152	2016-11-22 10:18:50	2016-11-22 10:18:50	MM	MMR	104	BM	Myanmar	Nay Pyi Taw	678500	53414374	AS	.mm	MMK	Kyat	95	#####	^(d{5})$	my	1327865	CN,LA,TH,BD,IN	\\r
154	2016-11-22 10:18:50	2016-11-22 10:18:50	NR	NRU	520	NR	Nauru	Yaren	21	10065	OC	.nr	AUD	Dollar	674			na,en-NR	2110425		\\r
155	2016-11-22 10:18:50	2016-11-22 10:18:50	NP	NPL	524	NP	Nepal	Kathmandu	140800	28951852	AS	.np	NPR	Rupee	977	#####	^(d{5})$	ne,en	1282988	CN,IN	\\r
156	2016-11-22 10:18:50	2016-11-22 10:18:50	NL	NLD	528	NL	Netherlands	Amsterdam	41526	16645000	EU	.nl	EUR	Euro	31	#### @@	^(d{4}[A-Z]{2})$	nl-NL,fy-NL	2750405	DE,BE	\\r
157	2016-11-22 10:18:50	2016-11-22 10:18:50	AN	ANT	530	NT	Netherlands Antilles	Willemstad	960	136197	NA	.an	ANG	Guilder	599			nl-AN,en,es	0	GP	\\r
158	2016-11-22 10:18:50	2016-11-22 10:18:50	NC	NCL	540	NC	New Caledonia	Noumea	19060	216494	OC	.nc	XPF	Franc	687	#####	^(d{5})$	fr-NC	2139685		\\r
159	2016-11-22 10:18:50	2016-11-22 10:18:50	NZ	NZL	554	NZ	New Zealand	Wellington	268680	4252277	OC	.nz	NZD	Dollar	64	####	^(d{4})$	en-NZ,mi	2186224		\\r
160	2016-11-22 10:18:50	2016-11-22 10:18:50	NI	NIC	558	NU	Nicaragua	Managua	129494	5995928	NA	.ni	NIO	Cordoba	505	###-###-#	^(d{7})$	es-NI,en	3617476	CR,HN	\\r
161	2016-11-22 10:18:50	2016-11-22 10:18:50	NE	NER	562	NG	Niger	Niamey	1267000	15878271	AF	.ne	XOF	Franc	227	####	^(d{4})$	fr-NE,ha,kr,dje	2440476	TD,BJ,DZ,LY,BF,NG,ML	\\r
162	2016-11-22 10:18:50	2016-11-22 10:18:50	NG	NGA	566	NI	Nigeria	Abuja	923768	154000000	AF	.ng	NGN	Naira	234	######	^(d{6})$	en-NG,ha,yo,ig,ff	2328926	TD,NE,BJ,CM	\\r
163	2016-11-22 10:18:50	2016-11-22 10:18:50	NU	NIU	570	NE	Niue	Alofi	260	2166	OC	.nu	NZD	Dollar	683			niu,en-NU	4036232		\\r
164	2016-11-22 10:18:50	2016-11-22 10:18:50	NF	NFK	574	NF	Norfolk Island	Kingston	34.6000000000000014	1828	OC	.nf	AUD	Dollar	672			en-NF	2155115		\\r
165	2016-11-22 10:18:50	2016-11-22 10:18:50	KP	PRK	408	KN	North Korea	Pyongyang	120540	22912177	AS	.kp	KPW	Won	850	###-###	^(d{6})$	ko-KP	1873107	CN,KR,RU	\\r
166	2016-11-22 10:18:50	2016-11-22 10:18:50	MP	MNP	580	CQ	Northern Mariana Islands	Saipan	477	53883	OC	.mp	USD	Dollar	+1-670			fil,tl,zh,ch-MP,en-MP	4041468		\\r
167	2016-11-22 10:18:50	2016-11-22 10:18:50	NO	NOR	578	NO	Norway	Oslo	324220	4985870	EU	.no	NOK	Krone	47	####	^(d{4})$	no,nb,nn,se,fi	3144096	FI,RU,SE	\\r
168	2016-11-22 10:18:50	2016-11-22 10:18:50	OM	OMN	512	MU	Oman	Muscat	212460	2967717	AS	.om	OMR	Rial	968	###	^(d{3})$	ar-OM,en,bal,ur	286963	SA,YE,AE	\\r
169	2016-11-22 10:18:50	2016-11-22 10:18:50	PK	PAK	586	PK	Pakistan	Islamabad	803940	184404791	AS	.pk	PKR	Rupee	92	#####	^(d{5})$	ur-PK,en-PK,pa,sd,ps,brh	1168579	CN,AF,IR,IN	\\r
170	2016-11-22 10:18:50	2016-11-22 10:18:50	PW	PLW	585	PS	Palau	Melekeok	458	19907	OC	.pw	USD	Dollar	680	96940	^(96940)$	pau,sov,en-PW,tox,ja,fil,zh	1559582		\\r
171	2016-11-22 10:18:50	2016-11-22 10:18:50	PS	PSE	275	WE	Palestinian Territory	East Jerusalem	5970	3800000	AS	.ps	ILS	Shekel	970			ar-PS	6254930	JO,IL	\\r
172	2016-11-22 10:18:50	2016-11-22 10:18:50	PA	PAN	591	PM	Panama	Panama City	78200	3410676	NA	.pa	PAB	Balboa	507			es-PA,en	3703430	CR,CO	\\r
173	2016-11-22 10:18:50	2016-11-22 10:18:50	PG	PNG	598	PP	Papua New Guinea	Port Moresby	462840	6064515	OC	.pg	PGK	Kina	675	###	^(d{3})$	en-PG,ho,meu,tpi	2088628	ID	\\r
174	2016-11-22 10:18:50	2016-11-22 10:18:50	PY	PRY	600	PA	Paraguay	Asuncion	406750	6375830	SA	.py	PYG	Guarani	595	####	^(d{4})$	es-PY,gn	3437598	BO,BR,AR	\\r
175	2016-11-22 10:18:50	2016-11-22 10:18:50	PE	PER	604	PE	Peru	Lima	1285220	29907003	SA	.pe	PEN	Sol	51			es-PE,qu,ay	3932488	EC,CL,BO,BR,CO	\\r
176	2016-11-22 10:18:50	2016-11-22 10:18:50	PH	PHL	608	RP	Philippines	Manila	300000	99900177	AS	.ph	PHP	Peso	63	####	^(d{4})$	tl,en-PH,fil	1694008		\\r
177	2016-11-22 10:18:50	2016-11-22 10:18:50	PN	PCN	612	PC	Pitcairn	Adamstown	47	46	OC	.pn	NZD	Dollar	870			en-PN	4030699		\\r
179	2016-11-22 10:18:50	2016-11-22 10:18:50	PT	PRT	620	PO	Portugal	Lisbon	92391	10676000	EU	.pt	EUR	Euro	351	####-###	^(d{7})$	pt-PT,mwl	2264397	ES	\\r
180	2016-11-22 10:18:50	2016-11-22 10:18:50	PR	PRI	630	RQ	Puerto Rico	San Juan	9104	3916632	NA	.pr	USD	Dollar	+1-787 and	#####-####	^(d{9})$	en-PR,es-PR	4566966		\\r
181	2016-11-22 10:18:50	2016-11-22 10:18:50	QA	QAT	634	QA	Qatar	Doha	11437	840926	AS	.qa	QAR	Rial	974			ar-QA,es	289688	SA	\\r
182	2016-11-22 10:18:50	2016-11-22 10:18:50	CG	COG	178	CF	Republic of the Congo	Brazzaville	342000	3039126	AF	.cg	XAF	Franc	242			fr-CG,kg,ln-CG	2260494	CF,GA,CD,CM,AO	\\r
183	2016-11-22 10:18:50	2016-11-22 10:18:50	RE	REU	638	RE	Reunion	Saint-Denis	2517	776948	AF	.re	EUR	Euro	262	#####	^((97)|(98)(4|7|8)d{	fr-RE	935317		\\r
184	2016-11-22 10:18:50	2016-11-22 10:18:50	RO	ROU	642	RO	Romania	Bucharest	237500	21959278	EU	.ro	RON	Leu	40	######	^(d{6})$	ro,hu,rom	798549	MD,HU,UA,CS,BG,RS	\\r
185	2016-11-22 10:18:50	2016-11-22 10:18:50	RU	RUS	643	RS	Russia	Moscow	17100000	140702000	EU	.ru	RUB	Ruble	7	######	^(d{6})$	ru,tt,xal,cau,ady,kv,ce,tyv,cv,udm,tut,mns,bua,myv,mdf,chm,ba,inh,tut,kbd,krc,ava,sah,nog	2017370	GE,CN,BY,UA,KZ,LV,PL	\\r
186	2016-11-22 10:18:50	2016-11-22 10:18:50	RW	RWA	646	RW	Rwanda	Kigali	26338	11055976	AF	.rw	RWF	Franc	250			rw,en-RW,fr-RW,sw	49518	TZ,CD,BI,UG	\\r
187	2016-11-22 10:18:50	2016-11-22 10:18:50	BL	BLM	652	TB	Saint Barthelemy	Gustavia	21	8450	NA	.gp	EUR	Euro	590	### ###		fr	3578476		\\r
188	2016-11-22 10:18:50	2016-11-22 10:18:50	SH	SHN	654	SH	Saint Helena	Jamestown	410	7460	AF	.sh	SHP	Pound	290	STHL 1ZZ	^(STHL1ZZ)$	en-SH	3370751		\\r
189	2016-11-22 10:18:50	2016-11-22 10:18:50	KN	KNA	659	SC	Saint Kitts and Nevis	Basseterre	261	49898	NA	.kn	XCD	Dollar	+1-869			en-KN	3575174		\\r
190	2016-11-22 10:18:50	2016-11-22 10:18:50	LC	LCA	662	ST	Saint Lucia	Castries	616	160922	NA	.lc	XCD	Dollar	+1-758			en-LC	3576468		\\r
191	2016-11-22 10:18:50	2016-11-22 10:18:50	MF	MAF	663	RN	Saint Martin	Marigot	53	35925	NA	.gp	EUR	Euro	590	### ###		fr	3578421	SX	\\r
192	2016-11-22 10:18:50	2016-11-22 10:18:50	PM	SPM	666	SB	Saint Pierre and Miquelon	Saint-Pierre	242	7012	NA	.pm	EUR	Euro	508	#####	^(97500)$	fr-PM	3424932		\\r
193	2016-11-22 10:18:50	2016-11-22 10:18:50	VC	VCT	670	VC	Saint Vincent and the Grenadines	Kingstown	389	104217	NA	.vc	XCD	Dollar	+1-784			en-VC,fr	3577815		\\r
194	2016-11-22 10:18:50	2016-11-22 10:18:50	WS	WSM	882	WS	Samoa	Apia	2944	192001	OC	.ws	WST	Tala	685			sm,en-WS	4034894		\\r
195	2016-11-22 10:18:50	2016-11-22 10:18:50	SM	SMR	674	SM	San Marino	San Marino	61.2000000000000028	31477	EU	.sm	EUR	Euro	378	4789#	^(4789d)$	it-SM	3168068	IT	\\r
196	2016-11-22 10:18:50	2016-11-22 10:18:50	ST	STP	678	TP	Sao Tome and Principe	Sao Tome	1001	175808	AF	.st	STD	Dobra	239			pt-ST	2410758		\\r
197	2016-11-22 10:18:50	2016-11-22 10:18:50	SA	SAU	682	SA	Saudi Arabia	Riyadh	1960582	25731776	AS	.sa	SAR	Rial	966	#####	^(d{5})$	ar-SA	102358	QA,OM,IQ,YE,JO,AE,KW	\\r
198	2016-11-22 10:18:50	2016-11-22 10:18:50	SN	SEN	686	SG	Senegal	Dakar	196190	12323252	AF	.sn	XOF	Franc	221	#####	^(d{5})$	fr-SN,wo,fuc,mnk	2245662	GN,MR,GW,GM,ML	\\r
199	2016-11-22 10:18:50	2016-11-22 10:18:50	RS	SRB	688	RI	Serbia	Belgrade	88361	7344847	EU	.rs	RSD	Dinar	381	######	^(d{6})$	sr,hu,bs,rom	6290252	AL,HU,MK,RO,HR,BA,BG	\\r
200	2016-11-22 10:18:50	2016-11-22 10:18:50	CS	SCG	891	YI	Serbia and Montenegro	Belgrade	102350	10829175	EU	.cs	RSD	Dinar	381	#####	^(d{5})$	cu,hu,sq,sr	0	AL,HU,MK,RO,HR,BA,BG	\\r
201	2016-11-22 10:18:50	2016-11-22 10:18:50	SC	SYC	690	SE	Seychelles	Victoria	455	88340	AF	.sc	SCR	Rupee	248			en-SC,fr-SC	241170		\\r
202	2016-11-22 10:18:50	2016-11-22 10:18:50	SL	SLE	694	SL	Sierra Leone	Freetown	71740	5245695	AF	.sl	SLL	Leone	232			en-SL,men,tem	2403846	LR,GN	\\r
203	2016-11-22 10:18:50	2016-11-22 10:18:50	SG	SGP	702	SN	Singapore	Singapur	692.700000000000045	4701069	AS	.sg	SGD	Dollar	65	######	^(d{6})$	cmn,en-SG,ms-SG,ta-SG,zh-SG	1880251		\\r
204	2016-11-22 10:18:50	2016-11-22 10:18:50	SX	SXM	534	NN	Sint Maarten	Philipsburg	0	37429	NA	.sx	ANG	Guilder	599			nl,en	7609695	MF	\\r
205	2016-11-22 10:18:50	2016-11-22 10:18:50	SK	SVK	703	LO	Slovakia	Bratislava	48845	5455000	EU	.sk	EUR	Euro	421	###  ##	^(d{5})$	sk,hu	3057568	PL,HU,CZ,UA,AT	\\r
206	2016-11-22 10:18:50	2016-11-22 10:18:50	SI	SVN	705	SI	Slovenia	Ljubljana	20273	2007000	EU	.si	EUR	Euro	386	SI- ####	^(?:SI)*(d{4})$	sl,sh	3190538	HU,IT,HR,AT	\\r
207	2016-11-22 10:18:50	2016-11-22 10:18:50	SB	SLB	90	BP	Solomon Islands	Honiara	28450	559198	OC	.sb	SBD	Dollar	677			en-SB,tpi	2103350		\\r
208	2016-11-22 10:18:50	2016-11-22 10:18:50	SO	SOM	706	SO	Somalia	Mogadishu	637657	10112453	AF	.so	SOS	Shilling	252	@@  #####	^([A-Z]{2}d{5})$	so-SO,ar-SO,it,en-SO	51537	ET,KE,DJ	\\r
209	2016-11-22 10:18:50	2016-11-22 10:18:50	ZA	ZAF	710	SF	South Africa	Pretoria	1219912	49000000	AF	.za	ZAR	Rand	27	####	^(d{4})$	zu,xh,af,nso,en-ZA,tn,st,ts,ss,ve,nr	953987	ZW,SZ,MZ,BW,NA,LS	\\r
210	2016-11-22 10:18:50	2016-11-22 10:18:50	GS	SGS	239	SX	South Georgia and the South Sandwich Islands	Grytviken	3903	30	AN	.gs	GBP	Pound				en	3474415		\\r
211	2016-11-22 10:18:50	2016-11-22 10:18:50	KR	KOR	410	KS	South Korea	Seoul	98480	48422644	AS	.kr	KRW	Won	82	SEOUL ###-###	^(?:SEOUL)*(d{6})$	ko-KR,en	1835841	KP	\\r
212	2016-11-22 10:18:50	2016-11-22 10:18:50	SS	SSD	728	OD	South Sudan	Juba	644329	8260490	AF		SSP	Pound	211			en	7909807	CD,CF,ET,KE,SD,UG,	\\r
213	2016-11-22 10:18:50	2016-11-22 10:18:50	ES	ESP	724	SP	Spain	Madrid	504782	46505963	EU	.es	EUR	Euro	34	#####	^(d{5})$	es-ES,ca,gl,eu,oc	2510769	AD,PT,GI,FR,MA	\\r
214	2016-11-22 10:18:50	2016-11-22 10:18:50	LK	LKA	144	CE	Sri Lanka	Colombo	65610	21513990	AS	.lk	LKR	Rupee	94	#####	^(d{5})$	si,ta,en	1227603		\\r
215	2016-11-22 10:18:50	2016-11-22 10:18:50	SD	SDN	729	SU	Sudan	Khartoum	1861484	35000000	AF	.sd	SDG	Pound	249	#####	^(d{5})$	ar-SD,en,fia	366755	SS,TD,EG,ET,ER,LY,CF	\\r
216	2016-11-22 10:18:50	2016-11-22 10:18:50	SR	SUR	740	NS	Suriname	Paramaribo	163270	492829	SA	.sr	SRD	Dollar	597			nl-SR,en,srn,hns,jv	3382998	GY,BR,GF	\\r
217	2016-11-22 10:18:50	2016-11-22 10:18:50	SJ	SJM	744	SV	Svalbard and Jan Mayen	Longyearbyen	62049	2550	EU	.sj	NOK	Krone	47			no,ru	607072		\\r
218	2016-11-22 10:18:50	2016-11-22 10:18:50	SZ	SWZ	748	WZ	Swaziland	Mbabane	17363	1354051	AF	.sz	SZL	Lilangeni	268	@###	^([A-Z]d{3})$	en-SZ,ss-SZ	934841	ZA,MZ	\\r
219	2016-11-22 10:18:50	2016-11-22 10:18:50	SE	SWE	752	SW	Sweden	Stockholm	449964	9045000	EU	.se	SEK	Krona	46	SE-### ##	^(?:SE)*(d{5})$	sv-SE,se,sma,fi-SE	2661886	NO,FI	\\r
220	2016-11-22 10:18:50	2016-11-22 10:18:50	CH	CHE	756	SZ	Switzerland	Berne	41290	7581000	EU	.ch	CHF	Franc	41	####	^(d{4})$	de-CH,fr-CH,it-CH,rm	2658434	DE,IT,LI,FR,AT	\\r
221	2016-11-22 10:18:50	2016-11-22 10:18:50	SY	SYR	760	SY	Syria	Damascus	185180	22198110	AS	.sy	SYP	Pound	963			ar-SY,ku,hy,arc,fr,en	163843	IQ,JO,IL,TR,LB	\\r
222	2016-11-22 10:18:50	2016-11-22 10:18:50	TW	TWN	158	TW	Taiwan	Taipei	35980	22894384	AS	.tw	TWD	Dollar	886	#####	^(d{5})$	zh-TW,zh,nan,hak	1668284		\\r
223	2016-11-22 10:18:50	2016-11-22 10:18:50	TJ	TJK	762	TI	Tajikistan	Dushanbe	143100	7487489	AS	.tj	TJS	Somoni	992	######	^(d{6})$	tg,ru	1220409	CN,AF,KG,UZ	\\r
224	2016-11-22 10:18:50	2016-11-22 10:18:50	TZ	TZA	834	TZ	Tanzania	Dodoma	945087	41892895	AF	.tz	TZS	Shilling	255			sw-TZ,en,ar	149590	MZ,KE,CD,RW,ZM,BI,UG	\\r
225	2016-11-22 10:18:50	2016-11-22 10:18:50	TH	THA	764	TH	Thailand	Bangkok	514000	67089500	AS	.th	THB	Baht	66	#####	^(d{5})$	th,en	1605651	LA,MM,KH,MY	\\r
226	2016-11-22 10:18:50	2016-11-22 10:18:50	TG	TGO	768	TO	Togo	Lome	56785	6587239	AF	.tg	XOF	Franc	228			fr-TG,ee,hna,kbp,dag,ha	2363686	BJ,GH,BF	\\r
227	2016-11-22 10:18:50	2016-11-22 10:18:50	TK	TKL	772	TL	Tokelau		10	1466	OC	.tk	NZD	Dollar	690			tkl,en-TK	4031074		\\r
228	2016-11-22 10:18:50	2016-11-22 10:18:50	TO	TON	776	TN	Tonga	Nukualofa	748	122580	OC	.to	TOP	Paanga	676			to,en-TO	4032283		\\r
229	2016-11-22 10:18:50	2016-11-22 10:18:50	TT	TTO	780	TD	Trinidad and Tobago	Port of Spain	5128	1228691	NA	.tt	TTD	Dollar	+1-868			en-TT,hns,fr,es,zh	3573591		\\r
230	2016-11-22 10:18:50	2016-11-22 10:18:50	TN	TUN	788	TS	Tunisia	Tunis	163610	10589025	AF	.tn	TND	Dinar	216	####	^(d{4})$	ar-TN,fr	2464461	DZ,LY	\\r
231	2016-11-22 10:18:50	2016-11-22 10:18:50	TR	TUR	792	TU	Turkey	Ankara	780580	77804122	AS	.tr	TRY	Lira	90	#####	^(d{5})$	tr-TR,ku,diq,az,av	298795	SY,GE,IQ,IR,GR,AM,AZ	\\r
232	2016-11-22 10:18:50	2016-11-22 10:18:50	TM	TKM	795	TX	Turkmenistan	Ashgabat	488100	4940916	AS	.tm	TMT	Manat	993	######	^(d{6})$	tk,ru,uz	1218197	AF,IR,UZ,KZ	\\r
233	2016-11-22 10:18:50	2016-11-22 10:18:50	TC	TCA	796	TK	Turks and Caicos Islands	Cockburn Town	430	20556	NA	.tc	USD	Dollar	+1-649	TKCA 1ZZ	^(TKCA 1ZZ)$	en-TC	3576916		\\r
234	2016-11-22 10:18:50	2016-11-22 10:18:50	TV	TUV	798	TV	Tuvalu	Funafuti	26	10472	OC	.tv	AUD	Dollar	688			tvl,en,sm,gil	2110297		\\r
235	2016-11-22 10:18:50	2016-11-22 10:18:50	VI	VIR	850	VQ	U.S. Virgin Islands	Charlotte Amalie	352	108708	NA	.vi	USD	Dollar	+1-340			en-VI	4796775		\\r
236	2016-11-22 10:18:50	2016-11-22 10:18:50	UG	UGA	800	UG	Uganda	Kampala	236040	33398682	AF	.ug	UGX	Shilling	256			en-UG,lg,sw,ar	226074	TZ,KE,SS,CD,RW	\\r
237	2016-11-22 10:18:50	2016-11-22 10:18:50	UA	UKR	804	UP	Ukraine	Kiev	603700	45415596	EU	.ua	UAH	Hryvnia	380	#####	^(d{5})$	uk,ru-UA,rom,pl,hu	690791	PL,MD,HU,SK,BY,RO,RU	\\r
238	2016-11-22 10:18:50	2016-11-22 10:18:50	AE	ARE	784	AE	United Arab Emirates	Abu Dhabi	82880	4975593	AS	.ae	AED	Dirham	971			ar-AE,fa,en,hi,ur	290557	SA,OM	\\r
239	2016-11-22 10:18:50	2016-11-22 10:18:50	GB	GBR	826	UK	United Kingdom	London	244820	62348447	EU	.uk	GBP	Pound	44	@# #@@|@## #@@|@@# #	^(([A-Z]d{2}[A-Z]{2}	en-GB,cy-GB,gd	2635167	IE	\\r
240	2016-11-22 10:18:50	2016-11-22 10:18:50	US	USA	840	US	United States	Washington	9629091	310232863	NA	.us	USD	Dollar	1	#####-####	^(d{9})$	en-US,es-US,haw,fr	6252001	CA,MX,CU	\\r
241	2016-11-22 10:18:50	2016-11-22 10:18:50	UM	UMI	581		United States Minor Outlying Islands		0	0	OC	.um	USD	Dollar	1			en-UM	5854968		\\r
242	2016-11-22 10:18:50	2016-11-22 10:18:50	UY	URY	858	UY	Uruguay	Montevideo	176220	3477000	SA	.uy	UYU	Peso	598	#####	^(d{5})$	es-UY	3439705	BR,AR	\\r
243	2016-11-22 10:18:50	2016-11-22 10:18:50	UZ	UZB	860	UZ	Uzbekistan	Tashkent	447400	27865738	AS	.uz	UZS	Som	998	######	^(d{6})$	uz,ru,tg	1512440	TM,AF,KG,TJ,KZ	\\r
244	2016-11-22 10:18:50	2016-11-22 10:18:50	VU	VUT	548	NH	Vanuatu	Port Vila	12200	221552	OC	.vu	VUV	Vatu	678			bi,en-VU,fr-VU	2134431		\\r
245	2016-11-22 10:18:50	2016-11-22 10:18:50	VA	VAT	336	VT	Vatican	Vatican City	0.440000000000000002	921	EU	.va	EUR	Euro	379			la,it,fr	3164670	IT	\\r
246	2016-11-22 10:18:50	2016-11-22 10:18:50	VE	VEN	862	VE	Venezuela	Caracas	912050	27223228	SA	.ve	VEF	Bolivar	58	####	^(d{4})$	es-VE	3625428	GY,BR,CO	\\r
247	2016-11-22 10:18:50	2016-11-22 10:18:50	VN	VNM	704	VM	Vietnam	Hanoi	329560	89571130	AS	.vn	VND	Dong	84	######	^(d{6})$	vi,en,fr,zh,km	1562822	CN,LA,KH	\\r
248	2016-11-22 10:18:50	2016-11-22 10:18:50	WF	WLF	876	WF	Wallis and Futuna	Mata Utu	274	16025	OC	.wf	XPF	Franc	681	#####	^(986d{2})$	wls,fud,fr-WF	4034749		\\r
249	2016-11-22 10:18:50	2016-11-22 10:18:50	EH	ESH	732	WI	Western Sahara	El-Aaiun	266000	273008	AF	.eh	MAD	Dirham	212			ar,mey	2461445	DZ,MR,MA	\\r
250	2016-11-22 10:18:50	2016-11-22 10:18:50	YE	YEM	887	YM	Yemen	Sanaa	527970	23495361	AS	.ye	YER	Rial	967			ar-YE	69543	SA,OM	\\r
251	2016-11-22 10:18:50	2016-11-22 10:18:50	ZM	ZMB	894	ZA	Zambia	Lusaka	752614	13460305	AF	.zm	ZMK	Kwacha	260	#####	^(d{5})$	en-ZM,bem,loz,lun,lue,ny,toi	895949	ZW,TZ,MZ,CD,NA,MW,AO	\\r
252	2016-11-22 10:18:50	2016-11-22 10:18:50	ZW	ZWE	716	ZI	Zimbabwe	Harare	390580	11651858	AF	.zw	ZWL	Dollar	263			en-ZW,sn,nr,nd	878675	ZA,MZ,BW,ZM	\\r
\.


--
-- Name: countries_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('countries_id_seq', 253, false);


--
-- Data for Name: email_templates; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY email_templates (id, created_at, updated_at, name, display_name, description, "from", reply_to, subject, email_variables, html_email_content, text_email_content) FROM stdin;
1	2016-05-30 11:13:01	2016-05-30 11:13:01	welcomemail	Welcome Mail	we will send this mail, when user register in this site and get activate.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Welcome to ##SITE_NAME##	SITE_NAME, SITE_URL,USERNAME, SUPPORT_EMAIL,SITE_URL	Hi ##USERNAME##,\r\n\r\n  We wish to say a quick hello and thanks for registering at ##SITE_NAME##.\r\n  \r\n  If you did not request this account and feel this is in error, please contact us at ##SUPPORT_EMAIL##\r\n\r\nThanks,\r\n\r\n##SITE_NAME##\r\n##SITE_URL##	Hi ##USERNAME##,\r\n\r\n  We wish to say a quick hello and thanks for registering at ##SITE_NAME##.\r\n  \r\n  If you did not request this account and feel this is in error, please contact us at ##SUPPORT_EMAIL##\r\n\r\nThanks,\r\n\r\n##SITE_NAME##\r\n##SITE_URL##
2	2016-05-30 11:21:09	2016-05-30 11:21:09	activationrequest	Activation Request	we will send this mail,\r\nwhen user registering an account he/she will get an activation\r\nrequest.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Please activate your ##SITE_NAME## account	SITE_NAME,SITE_URL,USERNAME,ACTIVATION_URL	Hi ##USERNAME##,\r\n\r\nYour account has been created. Please visit the following URL to activate your account.\r\n##ACTIVATION_URL##\r\n\r\nThanks,\r\n\r\n##SITE_NAME##\r\n##SITE_URL##	Hi ##USERNAME##,\r\n\r\nYour account has been created. Please visit the following URL to activate your account.\r\n##ACTIVATION_URL##\r\n\r\nThanks,\r\n\r\n##SITE_NAME##\r\n##SITE_URL##
3	2016-05-30 11:23:46	2016-05-30 11:23:46	changepassword	Change Password	we will send this mail\r\nto user, when the user change password.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Password changed	SITE_NAME,SITE_URL,PASSWORD,USERNAME	Hi ##USERNAME##,\r\n\r\nYour password has been changed\r\n\r\nYour new password:\r\n##PASSWORD##\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	Hi ##USERNAME##,\r\n\r\nYour password has been changed\r\n\r\nYour new password:\r\n##PASSWORD##\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##
4	2016-05-30 11:27:24	2016-05-30 11:27:24	forgotpassword	Forgot Password	we will send this mail, when\r\nuser submit the forgot password form.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Forgot password	USERNAME,PASSWORD,SITE_NAME,SITE_URL	Hi ##USERNAME##, \r\n\r\nWe have changed new password as per your requested.\r\n\r\nNew password: \r\n\r\n##PASSWORD##\r\n\r\nThanks, \r\n##SITE_NAME## \r\n##SITE_URL##	Hi ##USERNAME##, \r\n\r\nWe have changed new password as per your requested.\r\n\r\nNew password: \r\n\r\n##PASSWORD##\r\n\r\nThanks, \r\n##SITE_NAME## \r\n##SITE_URL##
5	2016-05-30 11:29:19	2016-05-30 11:29:19	adminuseredit	Admin User Edit	we will send this mail\r\ninto user, when admin edit users profile.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	[##SITE_NAME##] Profile updated	SITE_NAME,EMAIL,USERNAME	Hi ##USERNAME##,\r\n\r\nAdmin updated your profile in ##SITE_NAME## account.\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	Hi ##USERNAME##,\r\n\r\nAdmin updated your profile in ##SITE_NAME## account.\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##
6	2016-05-30 11:59:48	2016-05-30 11:59:48	adminpaidyourwithdrawalrequest	Paid Withdrawal Request	We will send mail to user once the admin paid.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Amount paid	SITE_NAME,USERNAME,SITE_URL,WITHDRAWAL_URL	Hi ##USERNAME##,\r\n\r\n  We have paid your amount as you have requested from withdrawal requested.\r\n\r\nWithdrawal:\r\n\r\n##WITHDRAWAL_URL##\r\n\r\n  \r\nThanks,\r\n\r\n##SITE_NAME##\r\n##SITE_URL##'	Hi ##USERNAME##,\r\n\r\n  We have paid your amount as you have requested from withdrawal requested.\r\n\r\nWithdrawal:\r\n\r\n##WITHDRAWAL_URL##\r\n\r\n  \r\nThanks,\r\n\r\n##SITE_NAME##\r\n##SITE_URL##'
7	2016-05-30 12:24:36	2016-05-30 12:24:36	adminuseractive	Admin User Active	we will send this mail to user, when a admin add a new user.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Your ##SITE_NAME## account has been activated	SITE_NAME,USERNAME, SITE_URL	Dear ##USERNAME##,\r\n\r\nYour account has been activated.\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	Dear ##USERNAME##,\r\n\r\nYour account has been activated.\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##
8	2016-05-30 12:24:36	2016-05-30 12:24:36	adminuserdeactive	Admin User Deactivate	We will send this mail to user, when user deactive by administator.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Your ##SITE_NAME## account has been deactivated	SITE_NAME,USERNAME, SITE_URL	Dear ##USERNAME##,\r\n\r\nYour ##SITE_NAME## account has been deactivated.\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	Dear ##USERNAME##,\r\n\r\nYour ##SITE_NAME## account has been deactivated.\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##
9	2016-05-30 12:24:36	2016-05-30 12:24:36	adminuserdelete	Admin User Delete	We will send this mail to user, when user delete by administrator.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Your ##SITE_NAME## account has been removed	SITE_NAME,USERNAME, SITE_URL	Dear ##USERNAME##,\r\n\r\nYour ##SITE_NAME## account has been removed.\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	Dear ##USERNAME##,\r\n\r\nYour ##SITE_NAME## account has been removed.\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##
10	2016-05-30 12:24:36	2016-05-30 12:24:36	newuserjoin	New User Join	we will send this mail to admin, when a new user registered in the site. For this you have to enable "admin mail after register" in the settings page.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	[##SITE_NAME##] New user joined	SITE_NAME,USERNAME, SITE_URL,USEREMAIL	Hi,\r\n\r\nA new user named "##USERNAME##" has joined in ##SITE_NAME##.\r\n\r\nUsername: ##USERNAME##\r\nEmail: ##USEREMAIL##\r\n\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	Hi,\r\n\r\nA new user named "##USERNAME##" has joined in ##SITE_NAME##.\r\n\r\nUsername: ##USERNAME##\r\nEmail: ##USEREMAIL##\r\n\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##
11	2016-05-30 12:24:36	2016-05-30 12:24:36	adminchangepassword	Admin Change Password	we will send this mail to user, when admin change users password.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Password changed	SITE_NAME,PASSWORD,USERNAME, SITE_URL	Hi ##USERNAME##,\r\n\r\nAdmin reset your password for your  ##SITE_NAME## account.\r\n\r\nYour new password: ##PASSWORD##\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	Hi ##USERNAME##,\r\n\r\nAdmin reset your password for your  ##SITE_NAME## account.\r\n\r\nYour new password: ##PASSWORD##\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##
12	2016-05-30 11:27:24	2016-05-30 11:27:24	failedsocialuser	Failed Social User	we will send this mail, when user submit the forgot password form and the user users social network websites like twitter, facebook to register.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Forgot password request failed	SITE_NAME, SITE_URL,USEREMAIL	Hi ##USERNAME##, \r\n\r\nYour forgot password request was failed because you have registered via ##OTHER_SITE## site.\r\n\r\nThanks, \r\n##SITE_NAME## \r\n##SITE_URL##	Hi ##USERNAME##, \r\n\r\nYour forgot password request was failed because you have registered via ##OTHER_SITE## site.\r\n\r\nThanks, \r\n##SITE_NAME## \r\n##SITE_URL##
13	2016-05-30 12:24:36	2016-05-30 12:24:36	contactus	Contact Us	We will send this mail to admin, when user submit any contact form.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	[##SITE_NAME##] ##SUBJECT##	FIRST_NAME ,LAST_NAME,FROM_EMAIL,IP,TELEPHONE, MESSAGE, SUBJECT,SITE_NAME,SITE_URL	##MESSAGE##\r\n\r\n----------------------------------------------------\r\nFirst Name   : ##FIRST_NAME##  \r\nLast Name    : ##LAST_NAME## \r\nEmail        : ##FROM_EMAIL##\r\nTelephone    : ##TELEPHONE##\r\nIP           : ##IP##\r\nWhois        : http://whois.sc/##IP##\r\n\r\n----------------------------------------------------\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	##MESSAGE##\r\n\r\n----------------------------------------------------\r\nFirst Name   : ##FIRST_NAME##  \r\nLast Name    : ##LAST_NAME## \r\nEmail        : ##FROM_EMAIL##\r\nTelephone    : ##TELEPHONE##\r\nIP           : ##IP##\r\nWhois        : http://whois.sc/##IP##\r\n\r\n----------------------------------------------------\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##
14	2016-05-30 12:24:36	2016-05-30 12:24:36	adminuseradd	Admin User Add	we will send this mail to user, when a admin add a new user.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Welcome to ##SITE_NAME##	SITE_NAME, USERNAME, PASSWORD, LOGINLABEL, USEDTOLOGIN, SITE_URL	Dear ##USERNAME##,\r\n\r\n##SITE_NAME## team added you as a user in ##SITE_NAME##.\r\n\r\nYour account details.\r\n##LOGINLABEL##:##USEDTOLOGIN##\r\nPassword:##PASSWORD##\r\n\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	Dear ##USERNAME##,\r\n\r\n##SITE_NAME## team added you as a user in ##SITE_NAME##.\r\n\r\nYour account details.\r\n##LOGINLABEL##:##USEDTOLOGIN##\r\nPassword:##PASSWORD##\r\n\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##
15	2016-05-30 11:27:24	2016-05-30 11:27:24	failledforgotpassword	Failed Forgot Password	we will send this mail, when user submit the forgot password form.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Failed Forgot Password	SITE_NAME, SITE_URL,USEREMAIL	Hi there,\r\n\r\nYou (or someone else) entered this email address when trying to change the password of an ##USEREMAIL## account.\r\n\r\nHowever, this email address is not in our registered users and therefore the attempted password request has failed. If you are our customer and were expecting this email, please try again using the email you gave when opening your account.\r\n\r\nIf you are not an ##SITE_NAME## customer, please ignore this email. If you did not request this action and feel this is an error, please contact us ##SUPPORT_EMAIL##.\r\n\r\nThanks, \r\n##SITE_NAME## \r\n##SITE_URL##	Hi there,\r\n\r\nYou (or someone else) entered this email address when trying to change the password of an ##USEREMAIL## account.\r\n\r\nHowever, this email address is not in our registered users and therefore the attempted password request has failed. If you are our customer and were expecting this email, please try again using the email you gave when opening your account.\r\n\r\nIf you are not an ##SITE_NAME## customer, please ignore this email. If you did not request this action and feel this is an error, please contact us ##SUPPORT_EMAIL##.\r\n\r\nThanks, \r\n##SITE_NAME## \r\n##SITE_URL##
17	2016-08-29 11:13:01	2016-08-29 11:13:01	messagereceived	messagereceived.	we will send this mail, when user received message.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	You have one new message	SITE_NAME, SITE_URL,USERNAME, SUPPORT_EMAIL,SITE_URL,OTHER_USER,MESSAGE_URL	Hi ##USERNAME##,\r\n\r\n##OTHER_USER## has sent message to you. Please click below link to view your message\r\n\r\n##MESSAGE_URL##\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	Hi ##USERNAME##,\r\n\r\n##OTHER_USER## has sent message to you. Please click below link to view your message\r\n\r\n##MESSAGE_URL##\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##', 'Hi ##USERNAME##,\r\n\r\n##OTHER_USER## has sent message to you. Please click below link to view your message\r\n\r\n##MESSAGE_URL##\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##
19	2016-12-07 06:16:22	2016-12-07 06:16:22	purchasepackage	Purchase package	We will send this mail, when user create the Package	##FROM_EMAIL##	##REPLY_TO_EMAIL##	[##USER_NAME##] Purchase new package	SITE_NAME,USERNAME, SITE_URL,USEREMAIL,PACKAGE_NAME,VALIDITY_DAYS,AMOUNT,ADDITIONAL_ADS_ALLOWED,CREDIT_POINT,POINTS_VALID_DAYS	Hi ##USERNAME##,\r\n\r\nYou have purchased new package (##PACKAGE_NAME##).\r\n\r\nPackage Name : ##PACKAGE_NAME##\r\nValidity Days : ##VALIDITY_DAYS##\r\nAmount : ##AMOUNT##\r\nAdditional ads allowed : ##ADDITIONAL_ADS_ALLOWED##\r\nCredit Points: ##CREDIT_POINT##\r\nPoints Valid Days: ##POINTS_VALID_DAYS##\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	Hi ##USERNAME##,\r\n\r\nYou have purchased new package (##PACKAGE_NAME##).\r\n\r\nPackage Name : ##PACKAGE_NAME##\r\nValidity Days : ##VALIDITY_DAYS##\r\nAmount : ##AMOUNT##\r\nAdditional ads allowed : ##ADDITIONAL_ADS_ALLOWED##\r\nCredit Points: ##CREDIT_POINT##\r\nPoints Valid Days: ##POINTS_VALID_DAYS##\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##
20	2016-12-07 11:29:32	2016-12-07 11:29:32	purchaseextras	Purchase extras	We will send this mail, when user create the add extra days	##FROM_EMAIL##	##REPLY_TO_EMAIL##	[##USER_NAME##] Purchase extra days	SITE_NAME,USERNAME, SITE_URL,USEREMAIL,AD_EXTRA_NAME,CATEGORY_NAME,DAYS,AMOUNT	Hi ##USERNAME##,\r\n\r\nYou have purchase extras days\r\nAd extra name : ##AD_EXTRA_NAME##\r\nCategory name : ##CATEGORY_NAME##\r\nDays : ##DAYS##\r\nAmount : ##AMOUNT##\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	Hi ##USERNAME##,\r\n\r\nYou have purchase extras days\r\nAd extra name : ##AD_EXTRA_NAME##\r\nCategory name : ##CATEGORY_NAME##\r\nDays : ##DAYS##\r\nAmount : ##AMOUNT##\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##
21	2016-12-28 11:23:46	2016-12-28 11:23:46	adFavorite	AdFavorite	we will send this mail\r\nto user, when favorite ad amount get reduced	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Price Reduced for your Favorite Ad	SITE_NAME,SITE_URL,USERNAME,AD	Hi ##USERNAME##,\r\n\r\nYour Favorite Ad ##AD_NAME## price was reduced. Please check below Ad URL.\r\n\r\n##AD_URL##\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	Hi ##USERNAME##,\r\n\r\nYour Favorite Ad ##AD_NAME## price was reduced. Please check below Ad URL\r\n\r\n##AD_URL##\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##
22	2016-12-28 11:23:46	2016-12-28 11:23:46	adsearch	Ad Search	we will send this mail\r\nto user, when new Ad added using saved search keywords	##FROM_EMAIL##	##REPLY_TO_EMAIL##	New Ad Posted	USERNAME,SITE_NAME,SITE_URL	Hi ##USERNAME##,\r\n\r\nNew Ad ##AD_NAME## was posted. Please check below Ad URL.\r\n\r\n##AD_URL##\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	Hi ##USERNAME##,\r\n\r\nNew Ad ##AD_NAME## was posted. Please check below Ad URL.\r\n\r\n##AD_URL##\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##
18	2016-12-07 11:29:32	2016-12-07 11:29:32	createad	Create Ad	We will send this mail, when user create the Ads	##FROM_EMAIL##	##REPLY_TO_EMAIL##	[##USERNAME##] create Ad	SITE_NAME,USERNAME, SITE_URL,USEREMAIL,AD_NAME,CATEGORY_NAME,ADVERTISER_TYPE,PRICE,DESCRIPTION,ADVERTISER_NAME,PHONE_NUMBER,AD_URL	Hi ##USERNAME##,\r\n\r\nYou have create Ad(##AD_NAME##).\r\n\r\nAd Name : ##AD_NAME##\r\nCategory Name : ##CATEGORY_NAME##\r\nAdvertiser Type : ##ADVERTISER_TYPE##\r\nPrice : ##PRICE##\r\nDescription : ##DESCRIPTION##\r\nAdvertiser Name : ##ADVERTISER_NAME##\r\nPhone Number : ##PHONE_NUMBER##\r\n\r\n##AD_URL##\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	Hi ##USERNAME##,\r\n\r\nYou have create Ad(##AD_NAME##).\r\n\r\nAd Name : ##AD_NAME##\r\nCategory Name : ##CATEGORY_NAME##\r\nAdvertiser Type : ##ADVERTISER_TYPE##\r\nPrice : ##PRICE##\r\nDescription : ##DESCRIPTION##\r\nAdvertiser Name : ##ADVERTISER_NAME##\r\nPhone Number : ##PHONE_NUMBER##\r\n\r\n##AD_URL##\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##
23	2016-08-29 11:13:01	2016-08-29 11:13:01	newadposttoadmin	newadposttoadmin.	we will send this mail, when user add new ad post.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	New Ad posted	SITE_NAME, SITE_URL,USERNAME, SUPPORT_EMAIL,SITE_URL,OTHER_USER,AD_URL	Hi ##USERNAME##,\\r\\n\\r\\n##OTHER_USER## has posted new Ad. Please click below link to view the ad.\\r\\n##AD_URL##\\r\\n\\r\\nThanks,\\r\\n##SITE_NAME##\\r\\n##SITE_URL##	Hi ##USERNAME##,\\r\\n\\r\\n##OTHER_USER## has posted new Ad. Please click below link to view the ad.\\r\\n##AD_URL##\\r\\n\\r\\nThanks,\\r\\n##SITE_NAME##\\r\\n##SITE_URL##
24	2016-08-29 11:13:01	2016-08-29 11:13:01	newadposttouser	newadposttouser.	we will send this mail, when  add new ad post under user search category.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	New Ad posted	SITE_NAME, SITE_URL,USERNAME, SUPPORT_EMAIL,SITE_URL,AD_URL	Hi ##USERNAME##,\\r\\n\\r\\nNew Ad is posted. Please check the below link to view the ad.\\r\\n##AD_URL##\\r\\n\\r\\nThanks,\\r\\n##SITE_NAME##\\r\\n##SITE_URL##	Hi ##USERNAME##,\\r\\n\\r\\nNew Ad is posted. Please check below link to view the ad.\\r\\n##AD_URL##\\r\\n\\r\\nThanks,\\r\\n##SITE_NAME##\\r\\n##SITE_URL##
25	2016-08-29 11:13:01	2016-08-29 11:13:01	contacttoadowner	contacttoadowner.	we will send this mail, when  user try to contact ad posted owner.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	user trying to contact you	SITE_NAME, SITE_URL,USERNAME, SUPPORT_EMAIL,SITE_URL,OTHER_USER,AD_URL	Hi ##USERNAME##,\\r\\n\\r\\n##OTHER_USER## has try to contact you for below your Ad.\\r\\n##AD_URL##\\r\\n\\r\\nThanks,\\r\\n##SITE_NAME##\\r\\n##SITE_URL##	Hi ##USERNAME##,\\r\\n\\r\\n##OTHER_USER## has try to contact you for below your Ad.\\r\\n##AD_URL##\\r\\n\\r\\nThanks,\\r\\n##SITE_NAME##\\r\\n##SITE_URL##
26	2016-08-29 11:13:01	2016-08-29 11:13:01	reminderadextra	reminderadextra.	we will send this mail for notification to reminder Ad Extra expire soon.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Your Ad Extra expire soon	SITE_NAME, SITE_URL,USERNAME, SUPPORT_EMAIL,SITE_URL,OTHER_USER	Hi ##USERNAME##,\\r\\n\\r\\nYour Ad Extra is expire soon.\\r\\n\\r\\nThanks,\\r\\n##SITE_NAME##\\r\\n##SITE_URL##	Hi ##USERNAME##,\\r\\n\\r\\nYour Ad Extra is expire soon\\r\\n\\r\\nThanks,\\r\\n##SITE_NAME##\\r\\n##SITE_URL##
27	2016-08-29 11:13:01	2016-08-29 11:13:01	reminderadpackage	reminderadpackage.	we will send this mail for notification to reminder Ad Package expire soon.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Your Ad Package expire soon	SITE_NAME, SITE_URL,USERNAME, SUPPORT_EMAIL,SITE_URL,OTHER_USER	Hi ##USERNAME##,\\r\\n\\r\\nYour Ad Package is expire soon.\\r\\n\\r\\nThanks,\\r\\n##SITE_NAME##\\r\\n##SITE_URL##	Hi ##USERNAME##,\\r\\n\\r\\nYour Ad Package is expire soon\\r\\n\\r\\nThanks,\\r\\n##SITE_NAME##\\r\\n##SITE_URL##
\.


--
-- Name: email_templates_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('email_templates_id_seq', 27, true);


--
-- Data for Name: form_fields; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY form_fields (id, created_at, updated_at, category_id, name, display_name, label, input_type_id, info, is_required, depends_on, depend_value, display_order, is_active, options) FROM stdin;
\.


--
-- Name: form_fields_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('form_fields_id_seq', 1, false);


--
-- Data for Name: input_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY input_types (id, created_at, updated_at, name) FROM stdin;
1	2016-08-13 08:47:05	2016-08-13 08:47:05	textInput
2	2016-08-13 08:47:05	2016-08-13 08:47:05	textArea
3	2016-08-13 08:47:05	2016-08-13 08:47:05	radio
4	2016-08-13 08:47:05	2016-08-13 08:47:05	checkbox
5	2016-08-13 08:47:05	2016-08-13 08:47:05	select
\.


--
-- Name: input_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('input_types_id_seq', 5, true);


--
-- Data for Name: ips; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY ips (id, created_at, updated_at, ip, host, city_id, state_id, country_id, timezone_id, latitude, longitude) FROM stdin;
\.


--
-- Name: ips_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('ips_id_seq', 1, true);


--
-- Data for Name: languages; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY languages (id, created_at, updated_at, name, iso2, iso3, is_active) FROM stdin;
1	2009-07-01 13:52:24	2009-07-01 13:52:24	Abkhazian	ab	abk	f
2	2009-07-01 13:52:24	2013-07-22 08:17:56	Afar	aa	aar	t
3	2009-07-01 13:52:24	2009-07-01 13:52:24	Afrikaans	af	afr	t
4	2009-07-01 13:52:24	2009-07-01 13:52:24	Akan	ak	aka	t
5	2009-07-01 13:52:25	2009-07-01 13:52:25	Albanian	sq	sqi	t
6	2009-07-01 13:52:24	2009-07-01 13:52:24	Amharic	am	amh	t
7	2009-07-01 13:52:24	2009-07-01 13:52:24	Arabic	ar	ara	t
8	2009-07-01 13:52:24	2009-07-01 13:52:24	Aragonese	an	arg	t
9	2009-07-01 13:52:25	2009-07-01 13:52:25	Armenian	hy	hye	t
10	2009-07-01 13:52:24	2009-07-01 13:52:24	Assamese	as	asm	t
11	2009-07-01 13:52:24	2009-07-01 13:52:24	Avaric	av	ava	t
12	2009-07-01 13:52:24	2009-07-01 13:52:24	Avestan	ae	ave	t
13	2009-07-01 13:52:24	2009-07-01 13:52:24	Aymara	ay	aym	t
14	2009-07-01 13:52:24	2009-07-01 13:52:24	Azerbaijani	az	aze	t
15	2009-07-01 13:52:24	2009-07-01 13:52:24	Bambara	bm	bam	t
16	2009-07-01 13:52:24	2009-07-01 13:52:24	Bashkir	ba	bak	t
17	2009-07-01 13:52:25	2009-07-01 13:52:25	Basque	eu	eus	t
18	2009-07-01 13:52:24	2009-07-01 13:52:24	Belarusian	be	bel	t
19	2009-07-01 13:52:24	2009-07-01 13:52:24	Bengali	bn	ben	t
20	2009-07-01 13:52:24	2009-07-01 13:52:24	Bihari	bh	bih	t
21	2009-07-01 13:52:24	2009-07-01 13:52:24	Bislama	bi	bis	t
22	2009-07-01 13:52:24	2009-07-01 13:52:24	Bosnian	bs	bos	t
23	2009-07-01 13:52:24	2009-07-01 13:52:24	Breton	br	bre	t
24	2009-07-01 13:52:24	2009-07-01 13:52:24	Bulgarian	bg	bul	t
25	2009-07-01 13:52:25	2009-07-01 13:52:25	Burmese	my	mya	t
26	2009-07-01 13:52:24	2011-10-22 08:13:07	Catalan	ca	cat	t
27	2009-07-01 13:52:25	2009-07-01 13:52:25	Chamorro	ch	cha	t
28	2009-07-01 13:52:25	2009-07-01 13:52:25	Chechen	ce	che	t
29	2009-07-01 13:52:25	2009-07-01 13:52:25	Chichewa	ny	nya	t
30	2009-07-01 13:52:25	2009-07-01 13:52:25	Chinese	zh	zho	t
31	2009-07-01 13:52:25	2009-07-01 13:52:25	Church Slavic	cu	chu	t
32	2009-07-01 13:52:25	2009-07-01 13:52:25	Chuvash	cv	chv	t
33	2009-07-01 13:52:25	2009-07-01 13:52:25	Cornish	kw	cor	t
34	2009-07-01 13:52:25	2009-07-01 13:52:25	Corsican	co	cos	t
35	2009-07-01 13:52:25	2009-07-01 13:52:25	Cree	cr	cre	t
36	2009-07-01 13:52:25	2009-07-01 13:52:25	Croatian	hr	hrv	t
37	2009-07-01 13:52:25	2009-07-01 13:52:25	Czech	cs	ces	t
38	2009-07-01 13:52:25	2011-05-23 12:29:53	Danish	da	dan	t
39	2009-07-01 13:52:25	2009-07-01 13:52:25	Divehi	dv	div	t
40	2009-07-01 13:52:25	2009-07-01 13:52:25	Dutch	nl	nld	t
41	2009-07-01 13:52:25	2009-07-01 13:52:25	Dzongkha	dz	dzo	t
42	2009-07-01 13:52:25	2009-07-01 13:52:25	English	en	eng	t
43	2009-07-01 13:52:25	2009-07-01 13:52:25	Esperanto	eo	epo	t
44	2009-07-01 13:52:25	2009-07-01 13:52:25	Estonian	et	est	t
45	2009-07-01 13:52:25	2009-07-01 13:52:25	Ewe	ee	ewe	t
46	2009-07-01 13:52:25	2009-07-01 13:52:25	Faroese	fo	fao	t
47	2009-07-01 13:52:25	2009-07-01 13:52:25	Fijian	fj	fij	t
48	2009-07-01 13:52:25	2009-07-01 13:52:25	Finnish	fi	fin	t
49	2009-07-01 13:52:25	2009-07-01 13:52:25	French	fr	fra	t
50	2009-07-01 13:52:25	2009-07-01 13:52:25	Fulah	ff	ful	t
51	2009-07-01 13:52:25	2009-07-01 13:52:25	Galician	gl	glg	t
52	2009-07-01 13:52:25	2009-07-01 13:52:25	Ganda	lg	lug	t
53	2009-07-01 13:52:25	2009-07-01 13:52:25	Georgian	ka	kat	t
54	2009-07-01 13:52:25	2009-07-01 13:52:25	German	de	deu	t
55	2009-07-01 13:52:25	2009-07-01 13:52:25	Greek	el	ell	t
56	2009-07-01 13:52:25	2009-07-01 13:52:25	GuaranÃƒÆ’Ã	gn	grn	t
57	2009-07-01 13:52:25	2009-07-01 13:52:25	Gujarati	gu	guj	t
58	2009-07-01 13:52:25	2009-07-01 13:52:25	Haitian	ht	hat	t
59	2009-07-01 13:52:25	2009-07-01 13:52:25	Hausa	ha	hau	t
60	2009-07-01 13:52:25	2009-07-01 13:52:25	Hebrew	he	heb	t
61	2009-07-01 13:52:25	2009-07-01 13:52:25	Herero	hz	her	t
62	2009-07-01 13:52:25	2009-07-01 13:52:25	Hindi	hi	hin	t
63	2009-07-01 13:52:25	2009-07-01 13:52:25	Hiri Motu	ho	hmo	t
64	2009-07-01 13:52:25	2009-07-01 13:52:25	Hungarian	hu	hun	t
65	2009-07-01 13:52:25	2009-07-01 13:52:25	Icelandic	is	isl	t
\.


--
-- Name: languages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('languages_id_seq', 66, false);


--
-- Data for Name: message_contents; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY message_contents (id, created_at, updated_at, subject, message) FROM stdin;
\.


--
-- Name: message_contents_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('message_contents_id_seq', 1, false);


--
-- Data for Name: messages; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY messages (id, created_at, updated_at, user_id, other_user_id, ad_id, message_content_id, is_sender, is_read, is_archived) FROM stdin;
\.


--
-- Name: messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('messages_id_seq', 1, false);


--
-- Name: money_transfer_account_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('money_transfer_account_id_seq', 1, false);


--
-- Data for Name: money_transfer_accounts; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY money_transfer_accounts (id, created_at, updated_at, user_id, account, is_active, is_primary) FROM stdin;
\.


--
-- Data for Name: oauth_access_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY oauth_access_tokens (access_token, client_id, user_id, expires, scope) FROM stdin;
\.


--
-- Data for Name: oauth_authorization_codes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY oauth_authorization_codes (authorization_code, client_id, user_id, redirect_uri, expires, scope) FROM stdin;
\.


--
-- Data for Name: oauth_clients; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY oauth_clients (id, created_at, updated_at, user_id, client_name, client_id, client_secret, redirect_uri, grant_types, scope, client_url, logo_url, tos_url, policy_url) FROM stdin;
1	2016-05-13 15:28:23	2016-05-13 15:28:23	1		2212711849319225	14uumnygq6xyorsry8l382o3myr852hb	\N	client_credentials password refresh_token authorization_code	\N	\N	\N	\N	\N
\.


--
-- Name: oauth_clients_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('oauth_clients_id_seq', 1, true);


--
-- Data for Name: oauth_jwt; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY oauth_jwt (client_id, subject, public_key) FROM stdin;
\.


--
-- Data for Name: oauth_refresh_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY oauth_refresh_tokens (refresh_token, client_id, user_id, expires, scope) FROM stdin;
\.


--
-- Data for Name: oauth_scopes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY oauth_scopes (scope, is_default) FROM stdin;
canUpdateUser	f
canViewUser	f
canListUserTransactions	f
canUserCreateUserCashWithdrawals	f
canUserViewUserCashWithdrawals	f
canUserListUserCashWithdrawals	f
canUserCreateMoneyTransferAccount	f
canUserUpdateMoneyTransferAccount	f
canUserViewMoneyTransferAccount	f
canUserListMoneyTransferAccount	f
canUserDeleteMoneyTransferAccount	f
canListWallet	f
canCreateWallet	f
canListUserNotification	f
canViewUserNotification	f
canUpdateUserNotification	f
canListUserAdExtra	f
canCreateUserAdExtra	f
canListUserAdPackage	f
canListMessage	f
canDeleteMessage	f
canViewMessage	f
canUpdateMessage	f
canCreateMessage	f
canDeleteAd	f
canUpdateAd	f
canCreateAd	f
canDeleteAdFavorite	f
canViewAdFavorite	f
canListAdFavorite	f
canCreateAdFavorite	f
canDeleteAdSearch	f
canViewAdSearch	f
canUpdateAdSearch	f
canListAdSearch	f
canCreateAdSearch	f
canCreateAdReport	f
canViewMyAd	f
canViewMyProfile	f
canListAdFormField	f
canCreateUserAdPackage	f
canCreateMoneyTransferAccount	f
canViewMoneyTransferAccount	f
canUpdateMoneyTransferAccount	f
canDeleteMoneyTransferAccount	f
canListAdReportType	f
canDeleteAttachment	f
canCheckCategoryPayment	f
canCreateValut	f
canUpdateValut	f
canDeleteValut	f
\.


--
-- Data for Name: pages; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY pages (id, created_at, updated_at, title, slug, content, meta_keywords, meta_description, is_active) FROM stdin;
1	2016-05-30 12:17:27	2016-05-30 12:17:27	Privacy Policy	privacy-policy	For each visitor to our Web page our Web server automatically recognizes no information regarding the domain or e-mail address.\r\n\r\nWe collect the e-mail addresses of those who post messages to our bulletin board the e-mail addresses of those who communicate with us via e-mail the e-mail addresses of those who make postings to our chat areas user-specific information on what pages consumers access or visit information volunteered by the consumer such as survey information and/or site registrations name and address telephone number.\r\n\r\nThe information we collect is disclosed when legally required to do so at the request of governmental authorities conducting an investigation to verify or enforce compliance with the policies governing our Website and applicable laws or to protect against misuse or unauthorized use of our Website to a successor entity in connection with a corporate merger consolidation sale of assets or other corporate change respecting the Website.\r\n\r\nWith respect to cookies. We use cookies to record session information such as items that consumers add to their shopping cart.\r\n\r\nIf you do not want to receive e-mail from us in the future please let us know by sending us e-mail at the above address.\r\n\r\nPersons who supply us with their telephone numbers on-line will only receive telephone contact from us with information regarding orders they have placed on-line. Please provide us with your name and phone number. We will be sure your name is removed from the list we share with other organizations.\r\n\r\nWith respect to Ad Servers. We do not partner with or have special relationships with any ad server companies.\r\n\r\nFrom time to time we may use customer information for new unanticipated uses not previously disclosed in our privacy notice. If our information practices change at some time in the future we will post the policy changes to our Web site to notify you of these changes and we will use for these new purposes only data collected from the time of the policy change forward. If you are concerned about how your information is used you should check back at our Web site periodically.\r\n\r\nUpon request we provide site visitors with access to transaction information (e.g. dates on which customers made purchases amounts and types of purchases) that we maintain about them.\r\n\r\nUpon request we offer visitors the ability to have inaccuracies corrected in contact information transaction information.\r\n\r\nWith respect to security. When we transfer and receive certain types of sensitive information such as financial or health information we redirect visitors to a secure server and will notify visitors through a pop-up screen on our site.\r\n\r\nIf you feel that this site is not following its stated information policy you may contact us at the above addresses or phone number.	privacy	privacy,policy	t
2	2016-05-30 12:17:27	2016-05-30 12:17:27	Terms and Conditions	terms-and-conditions	<h1>Web Site Terms and Conditions of Use </h1>\r\n\r\n1. Terms\r\nBy accessing this web site you are agreeing to be bound by these web site Terms and Conditions of Use all applicable laws and regulations and agree that you are responsible for compliance with any applicable local laws. If you do not agree with any of these terms you are prohibited from using or accessing this site. The materials contained in this web site are protected by applicable copyright and trade mark law.\r\n\r\n2. Use License\r\n\r\n    Permission is granted to temporarily download one copy of the materials (information or software) on crowdfunding web site for personal non-commercial transitory viewing only. This is the grant of a license not a transfer of title and under this license you may not:\r\n        modify or copy the materials;\r\n        use the materials for any commercial purpose or for any public display (commercial or non-commercial);\r\n        attempt to decompile or reverse engineer any software contained on crowdfunding web site;\r\n        remove any copyright or other proprietary notations from the materials; or\r\n        transfer the materials to another person or mirror the materials on any other server.\r\n    This license shall automatically terminate if you violate any of these restrictions and may be terminated by crowdfunding at any time. Upon terminating your viewing of these materials or upon the termination of this license you must destroy any downloaded materials in your possession whether in electronic or printed format.\r\n\r\n3. Disclaimer\r\nThe materials on crowdfunding web site are provided as is. crowdfunding makes no warranties expressed or implied and hereby disclaims and negates all other warranties including without limitation implied warranties or conditions of merchantability fitness for a particular purpose or non-infringement of intellectual property or other violation of rights. Further crowdfunding does not warrant or make any representations concerning the accuracy likely results or reliability of the use of the materials on its Internet web site or otherwise relating to such materials or on any sites linked to this site.\r\n4. Limitations\r\nIn no event shall crowdfunding or its suppliers be liable for any damages (including without limitation damages for loss of data or profit or due to business interruption) arising out of the use or inability to use the materials on crowdfunding Internet site even if crowdfunding or a crowdfunding authorized representative has been notified orally or in writing of the possibility of such damage. Because some jurisdictions do not allow limitations on implied warranties or limitations of liability for consequential or incidental damages these limitations may not apply to you.\r\n5. Revisions and Errata\r\nThe materials appearing on crowdfunding web site could include technical typographical or photographic errors. crowdfunding does not warrant that any of the materials on its web site are accurate complete or current. crowdfunding may make changes to the materials contained on its web site at any time without notice. crowdfunding does not however make any commitment to update the materials.\r\n6. Links\r\ncrowdfunding has not reviewed all of the sites linked to its Internet web site and is not responsible for the contents of any such linked site. The inclusion of any link does not imply endorsement by crowdfunding of the site. Use of any such linked web site is at the users own risk.\r\n7. Site Terms of Use Modifications\r\ncrowdfunding may revise these terms of use for its web site at any time without notice. By using this web site you are agreeing to be bound by the then current version of these Terms and Conditions of Use.	terms	terms	t
3	2016-05-30 12:24:36	2016-05-30 12:24:36	Acceptable Use Policy	aup	You are independently responsible for complying with all applicable laws in all of your actions related to your use of PayPal’s services, regardless of the purpose of the use. In addition, you must adhere to the terms of this Acceptable Use Policy.\r\n\r\n<h3> Prohibited Activities</h3>\r\n\r\nYou may not use the PayPal service for activities that:\r\n\r\nviolate any law, statute, ordinance or regulation\r\nrelate to sales of (a) narcotics, steroids, certain controlled substances or other products that present a risk to consumer safety, (b) drug paraphernalia, (c) items that encourage, promote, facilitate or instruct others to engage in illegal activity, (d) items that promote hate, violence, racial intolerance, or the financial exploitation of a crime, (e) items that are considered obscene, (f) items that infringe or violate any copyright, trademark, right of publicity or privacy or any other proprietary right under the laws of any jurisdiction, (g) certain sexually oriented materials or services, (h) ammunition, firearms, or certain firearm parts or accessories, or (i) certain weapons or knives regulated under applicable law\r\nrelate to transactions that (a) show the personal information of third parties in violation of applicable law, (b) support pyramid or ponzi schemes, matrix programs, other “get rich quick” schemes or certain multi-level marketing programs, (c) are associated with purchases of real property, annuities or lottery contracts, lay-away systems, off-shore banking or transactions to finance or refinance debts funded by a credit card, (d) are for the sale of certain items before the seller has control or possession of the item, (e) are by payment processors to collect payments on behalf of merchants, (f) are associated with the following Money Service Business Activities: the sale of traveler’s cheques or money orders, currency exchanges or cheque cashing, or (g) provide certain credit repair or debt settlement services\r\ninvolve the sales of products or services identified by government agencies to have a high likelihood of being fraudulent\r\nviolate applicable laws or industry regulations regarding the sale of (a) tobacco products, or (b) prescription drugs and devices\r\ninvolve gambling, gaming and/or any other activity with an entry fee and a prize, including, but not limited to casino games, sports betting, horse or greyhound racing, lottery tickets, other ventures that facilitate gambling, games of skill (whether or not it is legally defined as a lottery) and sweepstakes unless the operator has obtained prior approval from PayPal and the operator and customers are located exclusively in jurisdictions where such activities are permitted by law.\r\n	policy	policy	t
\.


--
-- Name: pages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('pages_id_seq', 3, true);


--
-- Data for Name: payment_gateway_settings; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY payment_gateway_settings (id, created_at, updated_at, payment_gateway_id, name, label, description, type, options, test_mode_value, live_mode_value) FROM stdin;
1	2016-11-21 12:17:27	2016-11-21 12:17:27	1	zazpay_merchant_id	Zazpay Merchant ID		text		\N	\N
2	2016-11-21 12:17:27	2016-11-21 12:17:27	1	zazpay_website_id	Zazpay Website ID		text		\N	\N
3	2016-11-21 12:17:27	2016-11-21 12:17:27	1	zazpay_secret_string	Zazpay Secret String		text		\N	\N
4	2016-11-21 12:17:27	2016-11-21 12:17:27	1	zazpay_api_key	Zazpay API Key		text		\N	\N
5	2016-11-21 12:17:27	2016-11-21 12:17:27	1	is_payment_via_api	Zazpay Payment		text		3	\N
6	2016-11-21 12:17:27	2016-11-21 12:17:27	1	zazpay_subscription_plan	Zazpay Subscription Plan		text		Pay as you go	\N
7	2017-05-16 11:29:32	2017-05-16 11:29:32	4	paypal_client_id	Client ID	PayPal Client ID	text		AaFSgezSJciunkPSb4CkRXq4peg90miVeOqfckaCsMOw57TcYfxRDnXXSctqWPZEWx-euOKJJ4wz6Hr-	\N
8	2017-05-16 11:29:32	2017-05-16 11:29:32	4	paypal_client_Secret	Client Secret	PayPal Client Secret	text		EGDZ_szCqR9VC1AlrYmN0YnVfsaX6qAVcoF1UI-RuRK5Die_1ji5blzUmUkrQ5ofh5P3v_x6th5mtq7G	CLIENT_SECRET
9	2017-05-17 11:29:32	2017-05-17 11:29:32	4	paypal_mode	Paypal Mode	Paypal mode	text		sandbox	\N
\.


--
-- Name: payment_gateway_settings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('payment_gateway_settings_id_seq', 9, true);


--
-- Data for Name: payment_gateways; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY payment_gateways (id, created_at, updated_at, name, display_name, slug, description, gateway_fees, transaction_count, payment_gateway_setting_count, is_test_mode, is_active, is_enable_for_wallet) FROM stdin;
2	2016-11-21 12:17:27	2016-11-21 12:17:27	Wallet	Wallet	wallet	Wallet Payment	0	0	0	t	t	t
3	2016-11-21 12:17:27	2016-11-21 12:17:27	Credits	Credits	credits	Credit Payment	0	0	0	t	t	f
4	2017-05-16 12:28:30	2017-05-16 12:28:30	PayPal	PayPal	paypal	Payment through PayPal	0	0	0	t	t	f
1	2016-11-22 12:17:27	2016-11-22 12:17:27	ZazPay	ZazPay	zazpay	Zazpay payment	0	0	0	t	t	f
\.


--
-- Name: payment_gateways_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('payment_gateways_id_seq', 3, true);


--
-- Data for Name: provider_users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY provider_users (id, created_at, updated_at, user_id, provider_id, access_token, access_token_secret, foreign_id, profile_picture_url, is_connected) FROM stdin;
\.


--
-- Name: provider_users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('provider_users_id_seq', 1, false);


--
-- Data for Name: providers; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY providers (id, created_at, updated_at, name, slug, secret_key, api_key, icon_class, button_class, is_active, "position") FROM stdin;
2	2016-05-28 14:31:37	2016-05-28 14:31:37	Twitter	twitter	\N	\N	fa-twitter	btn-twitter	t	2
3	2016-05-28 14:32:26	2016-05-28 14:32:35	Google	google	\N	\N	fa-google-plus	btn-google	t	3
1	2016-05-28 14:30:49	2016-06-14 09:55:50	Facebook	facebook			fa-facebook	btn-facebook	f	1
\.


--
-- Name: providers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('providers_id_seq', 3, true);


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY roles (id, created_at, updated_at, name, is_active) FROM stdin;
1	2016-06-13 16:02:55	2016-06-13 16:02:55	Admin	t
2	2016-06-13 16:02:55	2016-05-13 15:28:23	User	t
\.


--
-- Data for Name: search_keywords; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY search_keywords (id, created_at, updated_at, keyword, search_log_count) FROM stdin;
\.


--
-- Name: search_keywords_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('search_keywords_id_seq', 1, false);


--
-- Data for Name: setting_categories; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY setting_categories (id, created_at, updated_at, name, description) FROM stdin;
1	2016-05-30 12:17:27	2016-05-30 12:17:27	System	Manage site name, contact email, from email and reply to email.
2	2016-05-30 12:17:27	2016-05-30 12:17:27	SEO	Manage content, meta data and other information relevant to browsers or search engines.
3	2016-05-30 12:24:36	2016-05-30 12:24:36	Regional, Currency & Language	Manage site default language, currency and date-time format.
4	2016-05-30 12:25:53	2016-05-30 12:25:53	Account	Manage user account related settings\r\n
5	2016-05-30 12:17:27	2016-05-30 12:17:27	Wallet	Manage wallet related settings.\r\n
6	2016-05-30 12:17:27	2016-05-30 12:17:27	Withdrawals	Manage withdrawal related settings.\r\n
7	2016-05-30 12:17:27	2016-05-30 12:17:27	Third Party API	Manage third party API related settings\r\n
9	2016-05-30 12:24:36	2016-05-30 12:24:36	Revenue	Manage revenue related settings
8	2016-05-30 12:17:27	2016-05-30 12:17:27	Widget	Widgets for header,footer, project view page. Widgets can be in iframe and JavaScript embed code, etc (e.g., Twitter Widget, Facebook Like Box, Facebook Feeds Code, Google Ads).
\.


--
-- Name: setting_categories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('setting_categories_id_seq', 27, true);


--
-- Data for Name: settings; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY settings (id, created_at, updated_at, setting_category_id, name, value, description, type, label, "position", options) FROM stdin;
1	2016-05-30 12:25:53	2016-05-30 12:24:36	1	SITE_FROM_EMAIL	productdemo.admin@gmail.com	You can change this email address so that 'From' email will be changed in all email communication.	text	From Email Address	1	\N
5	2016-05-30 12:25:53	2016-05-30 12:25:53	1	SITE_NAME	Base	This name will be used in all pages and emails.	text	Site name	1	\N
11	2016-05-30 12:25:53	2016-05-30 12:25:53	3	SITE_LANGUAGE	en	The selected language will be used as default language all over the site.	select	Site language 	1	\N
12	2016-05-30 12:25:53	2016-05-30 12:24:36	3	CURRENCY_SYMBOL	$	Site Currency symbol of PayPal Currency Code. eg. $ for USD	text	Site Currency Symbol	1	\N
13	2016-05-30 12:17:27	2016-05-30 12:17:27	3	CURRENCY_CODE	USD	PayPal doesnt support all currencies; refer, <a href="https://www.paypal.com/cgi-bin/webscr?cmd=p/sell/mc/mc_wa-outside">https://www.paypal.com/cgi-bin/webscr?cmd=p/sell/mc/mc_wa-outside</a> for list of supported currencies in PayPal. The selected currency will be used as site default currency. (All payments, transaction will use this currency).	select	Currency Code	2	AUD,BRL,CAD,CZK,DKK,EUR,HKD,HUF,ILS,JPY,MXN,NOK,NZD,PHP,PLN,GBP,SGD,SEK,CHF,TWD,THB,TRY,USD
14	2016-05-30 12:24:36	2016-05-30 12:24:36	4	USER_IS_ALLOW_SWITCH_LANGUAGE	1	On enabling this feature, users can change site language to their choice.	checkbox	Enable User to Switch Language	1	\N
15	2016-05-30 12:17:27	2016-05-30 12:17:27	4	USER_USING_TO_LOGIN	username	You can select the option from the drop-downs to login into the site	select	Login Handle	1	username, email
20	2016-05-30 12:17:27	2016-05-30 12:17:27	4	USER_IS_EMAIL_VERIFICATION_FOR_REGISTER	0	On enabling this feature, the users are required to verify their email address which will be provided by them during registration. (Users cannot login until the email address is verified)	checkbox	Enable Email Verification After Registration	2	\N
19	2016-05-30 12:17:27	2016-05-30 12:17:27	4	USER_IS_ADMIN_ACTIVATE_AFTER_REGISTER	0	On enabling this feature, the user will not be able to login until the Admin (that will be you) approves their registration.	checkbox	Enable Administrator Approval After Registration	1	\N
21	2016-05-30 12:17:27	2016-05-30 12:17:27	4	USER_IS_AUTO_LOGIN_AFTER_REGISTER	0	On enabling this feature, users will be automatically logged-in after registration. (Only when "Email Verification" & "Admin Approval" is disabled)	checkbox	Enable Auto Login After Registration	3	\N
27	2016-05-30 12:17:27	2016-05-30 12:17:27	4	USER_IS_LOGOUT_AFTER_CHANGE_PASSWORD	0	By enabling this feature, When user changes the password, he will automatically log-out.	checkbox	Enable User to Logout after Password Change	5	\N
28	2016-05-30 12:17:27	2016-05-30 12:17:27	4	USER_IS_WELCOME_MAIL_AFTER_REGISTER	0	On enabling this feature, users will receive a welcome mail after registration.	checkbox	Enable Sending Welcome Mail After Registration	6	\N
22	2016-05-30 12:17:27	2016-05-30 12:17:27	5	WALLET_MIN_WALLET_AMOUNT	10	This is the minimum amount a user can add to his wallet.	text	Minimum wallet amount	1	\N
23	2016-05-30 12:24:36	2016-05-30 12:24:36	5	WALLET_MAX_WALLET_AMOUNT	20000	This is the maximum amount a user can add to his wallet. (If left empty, then, no maximum amount restrictions).	text	Maximum wallet amount	2	\N
24	2016-05-30 12:24:36	2016-05-30 12:24:36	6	USER_MINIMUM_WITHDRAW_AMOUNT	2	This is the minimum amount a user can withdraw from their wallet.	text	Minimum Withdrawal Amount	1	\N
25	2016-05-30 12:17:27	2016-05-30 12:17:27	6	USER_MAXIMUM_WITHDRAW_AMOUNT	10000	This is the maximum amount a user can withdraw from their wallet.	text	Maximum Withdrawal Amount	2	\N
6	2016-05-30 12:24:36	2016-05-30 12:17:27	7	GOOGLE_RECAPTCHA_CODE	6Le2SCQTAAAAABgGIgDxO1LiqN-emZKteGFj7Apa	Google recpatcha code.	text	Google Recaptcha Code	1	\N
26	2016-05-30 12:24:36	2016-05-30 12:24:36	9	SITE_COMMISSION	10	Site commission percentage wise	text	Site commission	1	\N
2	2016-05-30 12:25:53	2016-05-30 12:25:53	1	SITE_CONTACT_EMAIL	productdemo.admin@gmail.com	Contact email	test	Contact Email	3	\N
3	2016-05-30 12:17:27	2016-05-30 12:25:53	1	SITE_REPLY_TO_EMAIL	productdemo.admin@gmail.com	You can change this email address so that 'Reply To' email will be changed in all email communication.	text	Reply To Email Address	2	\N
4	2016-05-30 12:25:53	2016-05-30 12:25:53	1	SUPPORT_EMAIL	productdemo.admin@gmail.com	Support email	text	Support Email Address	4	\N
9	2016-05-30 12:17:27	2016-05-30 12:24:36	2	SITE_TRACKING_SCRIPT	<script type="text/javascript"> var _gaq = _gaq || []; _gaq.push(['_setAccount', 'UA-18572079-3']); _gaq.push(['_setDomainName', '.dev.agriya.com']); _gaq.push(['_setAllowAnchor', true]); _gaq.push(['_trackPageview']); _gaq.push(function() { href = window.location.search; href.replace(/(utm_source|utm_medium|utm_campaign|utm_term|utm_content)+=[^\\&]*/g, '').replace(/\\&+/g, '&').replace(/\\?\\&/g, '?').replace(/(\\?|\\&)$/g, ''); if (history.replaceState) history.replaceState(null, '', location.pathname + href + location.hash);}); (function() { var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true; ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s); })(); </script>	This is the site tracker script used for tracking and analyzing the data on how the people are getting into your website. e.g., Google Analytics. <a href="http://www.google.com/analytics" target="_blank">http://www.google.com/analytics</a>	textarea	Site Tracker Code	3	\N
29	2016-05-30 12:17:27	2016-05-30 12:17:27	4	USER_IS_ADMIN_MAIL_AFTER_REGISTER	0	On enabling this feature, notification mail will be sent to administrator on each registration.	checkbox	Enable Notify Administrator on Each Registration	7	\N
30	2016-05-30 12:17:27	2016-05-30 12:17:27	4	USER_IS_CAPTCHA_ENABLED_FORGOT_PASSWORD	0	On enabling this feature, captcha will display forgot password page.	checkbox	Enable Captcha Forgot password	8	\N
32	2016-05-30 12:25:53	2016-05-30 12:25:53	3	AMOUNT_PER_POINT	5	\N	text	Amount per points	1	\N
33	2016-11-22 15:56:38	2016-11-22 15:56:38	8	WIDGET_HOME_SCRIPT		This is the browse page script, used for display banners on browse page below ending soon\t	textarea	Code	1	\N
34	2016-11-22 15:56:38	2016-11-22 15:56:38	8	WIDGET_USER_SCRIPT		This is the Header part script, used for display banners on Header\t	textarea	Code	1	\N
35	2016-11-22 17:56:08	2016-11-22 17:56:08	8	WIDGET_VIEW_SCRIPT		Used for display banners on right side of restaurant view page	textarea	Ad View Widget	1	\N
7	2016-05-30 12:17:27	2016-05-30 12:24:36	2	META_KEYWORDS	oliker clone, classified	These are the keywords used for improving search engine results of your site. (Comma separated texts for multiple keywords.)	text	Keywords	1	\N
36	2016-11-22 17:58:41	2016-11-22 17:58:41	8	WIDGET_FOOTER_SCRIPT		This is the footer page script, used for display banners on footer page	textarea	Code	1	\N
37	2016-11-22 20:12:16	2016-11-22 20:12:16	1	SITE_FACEBOOK_URL	https://www.facebook.com/agriya		text	Site Facebook URL	4	
38	2016-11-22 20:13:57	2016-11-22 20:13:57	1	SITE_TWITTER_URL	https://twitter.com/agriya		text	Site Twitter URL	4	
39	2016-11-22 20:15:14	2016-11-22 20:15:14	1	SITE_YOUTUBE_URL	https://www.youtube.com/channel/UCcxmjGrb-E8CKXFv2RKOG5A		text	Site Youtube URL	4	
8	2016-05-30 12:24:36	2016-05-30 12:17:27	2	META_DESCRIPTION	Oliker helps you develop different clone in a oliker	These are the short descriptions for your site which will be used by the search engines on the search result pages to display preview snippets for a given page.	textarea	Description	2	
40	2016-11-22 20:13:57	2016-11-22 20:13:57	1	SITE_PINTEREST_URL	https://pinterest.com/agriya/		text	Site Pinterest URL	4	
41	2016-11-22 20:15:14	2016-11-22 20:15:14	1	SITE_GOOGLEPLUS_URL	https://plus.google.com/+AgriyaNews		text	Site Google Plus URL	4	
42	2016-12-29 12:25:53	2016-12-29 12:25:53	1	DAYS_TO_DISPLAY_POSTED_AD	30	Days to display posted ad	text	Days to display posted ad	10	\N
44	2017-02-02 11:51:39	2017-02-02 11:51:39	5	SITE_IS_ENABLE_SUDOPAY_PLUGIN	1	When site purchased ZazPay plugin	checkbox	Enable ZazPay plugin	0	\N
10	2016-05-30 12:24:36	2016-05-30 12:17:27	2	SITE_ROBOTS		Content for robots.txt; (search engine) robots specific instructions. Refer, <a href="http://www.robotstxt.org/">http://www.robotstxt.org/</a> for syntax and usage.	textarea	robots.txt	4	
43	2017-01-04 13:11:51	2017-01-04 13:11:51	1	SITE_ENABLED_PLUGINS	Common/Wallet,Common/Withdrawal,Common/Message,Common/ZazPay,Ad/Ad,Ad/AdExtra,Ad/AdFavorite,Ad/AdPackage,Ad/AdReport,Common/Paypal	\N	text	Site Plugin	1	\N
\.


--
-- Name: settings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('settings_id_seq', 44, true);


--
-- Data for Name: states; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY states (id, created_at, updated_at, country_id, name, slug, state_code, is_active) FROM stdin;
\.


--
-- Name: states_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('states_id_seq', 1, true);


--
-- Data for Name: transaction_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY transaction_types (id, created_at, updated_at, name, is_credit, message, message_for_other_user, message_for_admin) FROM stdin;
1	2016-12-02 16:38:04	2016-12-02 16:38:04	Amount added to wallet	t	Amount added to wallet		##USER## added amount to own wallet
\.


--
-- Name: transaction_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('transaction_types_id_seq', 1, true);


--
-- Data for Name: transactions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY transactions (id, created_at, updated_at, user_id, to_user_id, foreign_id, class, payment_gateway_id, amount, site_revenue, type) FROM stdin;
\.


--
-- Name: transactions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('transactions_id_seq', 1, false);


--
-- Data for Name: user_ad_extras; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY user_ad_extras (id, created_at, updated_at, user_id, ad_id, ad_extra_id, ad_extra_day_id, amount, payment_gateway_id, is_payment_completed, paypal_pay_key) FROM stdin;
\.


--
-- Name: user_ad_extras_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('user_ad_extras_id_seq', 1, false);


--
-- Data for Name: user_ad_packages; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY user_ad_packages (id, created_at, updated_at, user_id, ad_package_id, allowed_ad_count, points, used_points, expiry_date, amount, payment_gateway_id, is_payment_completed, used_ad_count, paypal_pay_key) FROM stdin;
\.


--
-- Name: user_ad_packages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('user_ad_packages_id_seq', 1, false);


--
-- Data for Name: user_cash_withdrawals; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY user_cash_withdrawals (id, created_at, updated_at, user_id, money_transfer_account_id, withdrawal_status_id, amount, remark) FROM stdin;
\.


--
-- Name: user_cash_withdrawals_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('user_cash_withdrawals_id_seq', 1, false);


--
-- Data for Name: user_notifications; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY user_notifications (id, created_at, updated_at, user_id, is_new_messages_received_notification_to_sms, is_new_messages_received_notification_to_email, is_new_ads_on_saved_searches_to_sms, is_new_ads_on_saved_searches_to_email, is_price_reduced_on_favorite_ads_to_sms, is_price_reduced_on_favorite_ads_to_email) FROM stdin;
\.


--
-- Name: user_notifications_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('user_notifications_id_seq', 1, false);


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY users (id, created_at, updated_at, role_id, username, email, password, provider_id, first_name, last_name, gender_id, dob, about_me, address, address1, city_id, state_id, country_id, zip_code, latitude, longitude, phone, mobile, available_wallet_amount, available_points, billing_company_name, billing_address, billing_postal_code_1, billing_postal_code_2, billing_city, billing_tin, invoice_name, invoice_address, invoice_postal_code_1, invoice_postal_code_2, invoice_city, last_login_ip_id, last_logged_in_time, is_active, is_email_confirmed, is_agree_terms_conditions, is_subscribed, is_turn_off_automatic_fields, is_hide_my_ads, ad_count, message_count, ad_search_count, ad_favorite_count, ad_active_count, hash) FROM stdin;
1	2016-06-14 18:20:16	2016-06-14 18:20:16	1	admin	productdemo.admin@gmail.com	$2y$12$7Bezs1GQsctRnC80lGMC7e4Q.g2opvnIyURlXhFqQ7urzI1voVp5y	0	\N	\N	0	\N	\N	\N	\N	0	0	0	\N	0.000000	0.000000	\N	\N	0	0	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	0	\N	t	t	f	t	f	f	0	0	0	0	0	
\.


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('users_id_seq', 2, true);


--
-- Data for Name: vaults; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY vaults (id, created_at, updated_at, masked_cc, credit_card_type, vault_key, vault_id, user_id, email, address, city, state, country, zip_code, phone, is_primary, credit_card_expire, expire_month, expire_year, cvv2, first_name, last_name, payment_type) FROM stdin;
\.


--
-- Name: vaults_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('vaults_id_seq', 1, false);


--
-- Data for Name: wallet_transaction_logs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY wallet_transaction_logs (id, created_at, updated_at, amount, foreign_id, class, status, payment_type) FROM stdin;
\.


--
-- Name: wallet_transaction_logs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('wallet_transaction_logs_id_seq', 1, false);


--
-- Data for Name: wallets; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY wallets (id, created_at, updated_at, user_id, amount, payment_gateway_id, is_payment_completed, paypal_pay_key) FROM stdin;
\.


--
-- Name: wallets_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('wallets_id_seq', 1, false);


--
-- Data for Name: withdrawal_statuses; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY withdrawal_statuses (id, created_at, updated_at, name) FROM stdin;
1	2016-08-17 05:22:17	2016-08-17 05:22:17	Pending
2	2016-08-17 05:22:17	2016-08-17 05:22:17	Approved
3	2016-08-17 05:22:17	2016-08-17 05:22:17	Rejected
\.


--
-- Data for Name: zazpay_ipn_logs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY zazpay_ipn_logs (id, created_at, updated_at, ip, post_variable) FROM stdin;
\.


--
-- Name: zazpay_ipn_logs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('zazpay_ipn_logs_id_seq', 1, false);


--
-- Data for Name: zazpay_payment_gateways; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY zazpay_payment_gateways (id, created_at, updated_at, zazpay_gateway_name, zazpay_gateway_id, zazpay_payment_group_id, zazpay_gateway_details, is_marketplace_supported) FROM stdin;
\.


--
-- Name: zazpay_payment_gateways_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('zazpay_payment_gateways_id_seq', 1, false);


--
-- Data for Name: zazpay_payment_gateways_users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY zazpay_payment_gateways_users (id, created_at, updated_at, user_id, zazpay_payment_gateway_id) FROM stdin;
\.


--
-- Name: zazpay_payment_gateways_users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('zazpay_payment_gateways_users_id_seq', 1, false);


--
-- Data for Name: zazpay_payment_groups; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY zazpay_payment_groups (id, created_at, updated_at, zazpay_group_id, name, thumb_url) FROM stdin;
\.


--
-- Name: zazpay_payment_groups_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('zazpay_payment_groups_id_seq', 1, false);


--
-- Data for Name: zazpay_transaction_logs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY zazpay_transaction_logs (id, created_at, updated_at, class, foreign_id, zazpay_pay_key, merchant_id, gateway_id, status, payment_type, buyer_id, buyer_email, buyer_address, amount, payment_id) FROM stdin;
\.


--
-- Name: zazpay_transaction_logs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('zazpay_transaction_logs_id_seq', 1, false);


--
-- Name: ad_extra_days_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_extra_days
    ADD CONSTRAINT ad_extra_days_id PRIMARY KEY (id);


--
-- Name: ad_extras_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_extras
    ADD CONSTRAINT ad_extras_id PRIMARY KEY (id);


--
-- Name: ad_favorites_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_favorites
    ADD CONSTRAINT ad_favorites_id PRIMARY KEY (id);


--
-- Name: ad_form_fields_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_form_fields
    ADD CONSTRAINT ad_form_fields_id PRIMARY KEY (id);


--
-- Name: ad_packages_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_packages
    ADD CONSTRAINT ad_packages_id PRIMARY KEY (id);


--
-- Name: ad_report_types_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_report_types
    ADD CONSTRAINT ad_report_types_id PRIMARY KEY (id);


--
-- Name: ad_reports_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_reports
    ADD CONSTRAINT ad_reports_id PRIMARY KEY (id);


--
-- Name: ad_searches_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_searches
    ADD CONSTRAINT ad_searches_id PRIMARY KEY (id);


--
-- Name: ad_views_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_views
    ADD CONSTRAINT ad_views_id PRIMARY KEY (id);


--
-- Name: ads_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ads
    ADD CONSTRAINT ads_id PRIMARY KEY (id);


--
-- Name: advertiser_types_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY advertiser_types
    ADD CONSTRAINT advertiser_types_id PRIMARY KEY (id);


--
-- Name: attachments_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY attachments
    ADD CONSTRAINT attachments_id PRIMARY KEY (id);


--
-- Name: banned_ips_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY banned_ips
    ADD CONSTRAINT banned_ips_id PRIMARY KEY (id);


--
-- Name: categories_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY categories
    ADD CONSTRAINT categories_id PRIMARY KEY (id);


--
-- Name: cities_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY cities
    ADD CONSTRAINT cities_id PRIMARY KEY (id);


--
-- Name: contacts_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY contacts
    ADD CONSTRAINT contacts_id PRIMARY KEY (id);


--
-- Name: countries_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY countries
    ADD CONSTRAINT countries_id PRIMARY KEY (id);


--
-- Name: email_templates_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY email_templates
    ADD CONSTRAINT email_templates_id PRIMARY KEY (id);


--
-- Name: form_fields_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY form_fields
    ADD CONSTRAINT form_fields_id PRIMARY KEY (id);


--
-- Name: input_types_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY input_types
    ADD CONSTRAINT input_types_id PRIMARY KEY (id);


--
-- Name: ips_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ips
    ADD CONSTRAINT ips_id PRIMARY KEY (id);


--
-- Name: languages_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY languages
    ADD CONSTRAINT languages_id PRIMARY KEY (id);


--
-- Name: message_contents_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY message_contents
    ADD CONSTRAINT message_contents_id PRIMARY KEY (id);


--
-- Name: messages_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY messages
    ADD CONSTRAINT messages_id PRIMARY KEY (id);


--
-- Name: money_transfer_account_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY money_transfer_accounts
    ADD CONSTRAINT money_transfer_account_id PRIMARY KEY (id);


--
-- Name: oauth_clients_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY oauth_clients
    ADD CONSTRAINT oauth_clients_id PRIMARY KEY (id);


--
-- Name: pages_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pages
    ADD CONSTRAINT pages_id PRIMARY KEY (id);


--
-- Name: payment_gateway_settings_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY payment_gateway_settings
    ADD CONSTRAINT payment_gateway_settings_id PRIMARY KEY (id);


--
-- Name: payment_gateways_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY payment_gateways
    ADD CONSTRAINT payment_gateways_id PRIMARY KEY (id);


--
-- Name: provider_users_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY provider_users
    ADD CONSTRAINT provider_users_id PRIMARY KEY (id);


--
-- Name: providers_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY providers
    ADD CONSTRAINT providers_id PRIMARY KEY (id);


--
-- Name: roles_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY roles
    ADD CONSTRAINT roles_id PRIMARY KEY (id);


--
-- Name: setting_categories_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY setting_categories
    ADD CONSTRAINT setting_categories_id PRIMARY KEY (id);


--
-- Name: settings_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY settings
    ADD CONSTRAINT settings_id PRIMARY KEY (id);


--
-- Name: states_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY states
    ADD CONSTRAINT states_id PRIMARY KEY (id);


--
-- Name: transaction_types_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY transaction_types
    ADD CONSTRAINT transaction_types_id PRIMARY KEY (id);


--
-- Name: transactions_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY transactions
    ADD CONSTRAINT transactions_id PRIMARY KEY (id);


--
-- Name: user_ad_extras_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_ad_extras
    ADD CONSTRAINT user_ad_extras_id PRIMARY KEY (id);


--
-- Name: user_ad_packages_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_ad_packages
    ADD CONSTRAINT user_ad_packages_id PRIMARY KEY (id);


--
-- Name: user_cash_withdrawals_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_cash_withdrawals
    ADD CONSTRAINT user_cash_withdrawals_id PRIMARY KEY (id);


--
-- Name: user_notifications_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_notifications
    ADD CONSTRAINT user_notifications_id PRIMARY KEY (id);


--
-- Name: users_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_id PRIMARY KEY (id);


--
-- Name: wallets_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY wallets
    ADD CONSTRAINT wallets_id PRIMARY KEY (id);


--
-- Name: withdrawal_statuses_id; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY withdrawal_statuses
    ADD CONSTRAINT withdrawal_statuses_id PRIMARY KEY (id);


--
-- Name: ad_extra_days_ad_extra_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ad_extra_days_ad_extra_id ON ad_extra_days USING btree (ad_extra_id);


--
-- Name: ad_extra_days_category_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ad_extra_days_category_id ON ad_extra_days USING btree (category_id);


--
-- Name: ad_favorites_ad_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ad_favorites_ad_id ON ad_favorites USING btree (ad_id);


--
-- Name: ad_favorites_ip_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ad_favorites_ip_id ON ad_favorites USING btree (ip_id);


--
-- Name: ad_favorites_user_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ad_favorites_user_id ON ad_favorites USING btree (user_id);


--
-- Name: ad_form_fields_ad_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ad_form_fields_ad_id ON ad_form_fields USING btree (ad_id);


--
-- Name: ad_form_fields_form_field_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ad_form_fields_form_field_id ON ad_form_fields USING btree (form_field_id);


--
-- Name: ad_packages_category_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ad_packages_category_id ON ad_packages USING btree (category_id);


--
-- Name: ad_reports_ad_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ad_reports_ad_id ON ad_reports USING btree (ad_id);


--
-- Name: ad_reports_ad_report_type_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ad_reports_ad_report_type_id ON ad_reports USING btree (ad_report_type_id);


--
-- Name: ad_searches_category_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ad_searches_category_id ON ad_searches USING btree (category_id);


--
-- Name: ad_searches_ip_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ad_searches_ip_id ON ad_searches USING btree (ip_id);


--
-- Name: ad_searches_user_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ad_searches_user_id ON ad_searches USING btree (user_id);


--
-- Name: ad_views_ad_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ad_views_ad_id ON ad_views USING btree (ad_id);


--
-- Name: ad_views_ip_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ad_views_ip_id ON ad_views USING btree (ip_id);


--
-- Name: ad_views_user_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ad_views_user_id ON ad_views USING btree (user_id);


--
-- Name: ads_advertiser_type_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ads_advertiser_type_id ON ads USING btree (advertiser_type_id);


--
-- Name: ads_category_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ads_category_id ON ads USING btree (category_id);


--
-- Name: ads_city_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ads_city_id ON ads USING btree (city_id);


--
-- Name: ads_country_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ads_country_id ON ads USING btree (country_id);


--
-- Name: ads_slug; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ads_slug ON ads USING btree (slug);


--
-- Name: ads_state_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ads_state_id ON ads USING btree (state_id);


--
-- Name: ads_user_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ads_user_id ON ads USING btree (user_id);


--
-- Name: attachments_class; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX attachments_class ON attachments USING btree (class);


--
-- Name: attachments_foreign_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX attachments_foreign_id ON attachments USING btree (foreign_id);


--
-- Name: categories_parent_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX categories_parent_id ON categories USING btree (parent_id);


--
-- Name: categories_slug; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX categories_slug ON categories USING btree (slug);


--
-- Name: cities_country_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX cities_country_id ON cities USING btree (country_id);


--
-- Name: cities_slug; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX cities_slug ON cities USING btree (slug);


--
-- Name: cities_state_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX cities_state_id ON cities USING btree (state_id);


--
-- Name: form_fields_category_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX form_fields_category_id ON form_fields USING btree (category_id);


--
-- Name: form_fields_input_type_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX form_fields_input_type_id ON form_fields USING btree (input_type_id);


--
-- Name: ips_city_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ips_city_id ON ips USING btree (city_id);


--
-- Name: ips_country_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ips_country_id ON ips USING btree (country_id);


--
-- Name: ips_state_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ips_state_id ON ips USING btree (state_id);


--
-- Name: ips_timezone_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ips_timezone_id ON ips USING btree (timezone_id);


--
-- Name: messages_ad_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX messages_ad_id ON messages USING btree (ad_id);


--
-- Name: messages_message_content_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX messages_message_content_id ON messages USING btree (message_content_id);


--
-- Name: messages_other_user_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX messages_other_user_id ON messages USING btree (other_user_id);


--
-- Name: messages_user_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX messages_user_id ON messages USING btree (user_id);


--
-- Name: money_transfer_account_user_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX money_transfer_account_user_id ON money_transfer_accounts USING btree (user_id);


--
-- Name: oauth_access_tokens_client_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX oauth_access_tokens_client_id ON oauth_access_tokens USING btree (client_id);


--
-- Name: oauth_access_tokens_user_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX oauth_access_tokens_user_id ON oauth_access_tokens USING btree (user_id);


--
-- Name: oauth_authorization_codes_client_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX oauth_authorization_codes_client_id ON oauth_authorization_codes USING btree (client_id);


--
-- Name: oauth_authorization_codes_user_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX oauth_authorization_codes_user_id ON oauth_authorization_codes USING btree (user_id);


--
-- Name: oauth_clients_user_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX oauth_clients_user_id ON oauth_clients USING btree (user_id);


--
-- Name: oauth_jwt_client_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX oauth_jwt_client_id ON oauth_jwt USING btree (client_id);


--
-- Name: oauth_refresh_tokens_client_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX oauth_refresh_tokens_client_id ON oauth_refresh_tokens USING btree (client_id);


--
-- Name: oauth_refresh_tokens_user_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX oauth_refresh_tokens_user_id ON oauth_refresh_tokens USING btree (user_id);


--
-- Name: pages_slug; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX pages_slug ON pages USING btree (slug);


--
-- Name: payment_gateway_settings_payment_gateway_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX payment_gateway_settings_payment_gateway_id ON payment_gateway_settings USING btree (payment_gateway_id);


--
-- Name: payment_gateways_slug; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX payment_gateways_slug ON payment_gateways USING btree (slug);


--
-- Name: provider_users_foreign_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX provider_users_foreign_id ON provider_users USING btree (foreign_id);


--
-- Name: provider_users_provider_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX provider_users_provider_id ON provider_users USING btree (provider_id);


--
-- Name: provider_users_user_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX provider_users_user_id ON provider_users USING btree (user_id);


--
-- Name: providers_slug; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX providers_slug ON providers USING btree (slug);


--
-- Name: settings_setting_category_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX settings_setting_category_id ON settings USING btree (setting_category_id);


--
-- Name: states_country_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX states_country_id ON states USING btree (country_id);


--
-- Name: states_slug; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX states_slug ON states USING btree (slug);


--
-- Name: transactions_class; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX transactions_class ON transactions USING btree (class);


--
-- Name: transactions_foreign_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX transactions_foreign_id ON transactions USING btree (foreign_id);


--
-- Name: transactions_payment_gateway_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX transactions_payment_gateway_id ON transactions USING btree (payment_gateway_id);


--
-- Name: transactions_user_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX transactions_user_id ON transactions USING btree (user_id);


--
-- Name: user_ad_extras_ad_extra_day_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX user_ad_extras_ad_extra_day_id ON user_ad_extras USING btree (ad_extra_day_id);


--
-- Name: user_ad_extras_ad_extra_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX user_ad_extras_ad_extra_id ON user_ad_extras USING btree (ad_extra_id);


--
-- Name: user_ad_extras_ad_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX user_ad_extras_ad_id ON user_ad_extras USING btree (ad_id);


--
-- Name: user_ad_extras_payment_gateway_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX user_ad_extras_payment_gateway_id ON user_ad_extras USING btree (payment_gateway_id);


--
-- Name: user_ad_extras_user_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX user_ad_extras_user_id ON user_ad_extras USING btree (user_id);


--
-- Name: user_ad_packages_ad_package_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX user_ad_packages_ad_package_id ON user_ad_packages USING btree (ad_package_id);


--
-- Name: user_ad_packages_payment_gateway_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX user_ad_packages_payment_gateway_id ON user_ad_packages USING btree (payment_gateway_id);


--
-- Name: user_ad_packages_user_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX user_ad_packages_user_id ON user_ad_packages USING btree (user_id);


--
-- Name: user_cash_withdrawals_money_transfer_account_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX user_cash_withdrawals_money_transfer_account_id ON user_cash_withdrawals USING btree (money_transfer_account_id);


--
-- Name: user_cash_withdrawals_user_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX user_cash_withdrawals_user_id ON user_cash_withdrawals USING btree (user_id);


--
-- Name: user_cash_withdrawals_withdrawal_status_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX user_cash_withdrawals_withdrawal_status_id ON user_cash_withdrawals USING btree (withdrawal_status_id);


--
-- Name: user_notifications_user_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX user_notifications_user_id ON user_notifications USING btree (user_id);


--
-- Name: users_city_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX users_city_id ON users USING btree (city_id);


--
-- Name: users_country_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX users_country_id ON users USING btree (country_id);


--
-- Name: users_email; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX users_email ON users USING btree (email);


--
-- Name: users_gender_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX users_gender_id ON users USING btree (gender_id);


--
-- Name: users_last_login_ip_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX users_last_login_ip_id ON users USING btree (last_login_ip_id);


--
-- Name: users_provider_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX users_provider_id ON users USING btree (provider_id);


--
-- Name: users_role_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX users_role_id ON users USING btree (role_id);


--
-- Name: users_state_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX users_state_id ON users USING btree (state_id);


--
-- Name: users_username; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX users_username ON users USING btree (username);


--
-- Name: vaults_user_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX vaults_user_id ON vaults USING btree (user_id);


--
-- Name: vaults_vault_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX vaults_vault_id ON vaults USING btree (vault_id);


--
-- Name: wallets_payment_gateway_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX wallets_payment_gateway_id ON wallets USING btree (payment_gateway_id);


--
-- Name: wallets_user_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX wallets_user_id ON wallets USING btree (user_id);


--
-- Name: ad_extra_days_ad_extra_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_extra_days
    ADD CONSTRAINT ad_extra_days_ad_extra_id_fkey FOREIGN KEY (ad_extra_id) REFERENCES ad_extras(id) ON DELETE SET NULL;


--
-- Name: ad_extra_days_category_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_extra_days
    ADD CONSTRAINT ad_extra_days_category_id_fkey FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL;


--
-- Name: ad_favorites_ad_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_favorites
    ADD CONSTRAINT ad_favorites_ad_id_fkey FOREIGN KEY (ad_id) REFERENCES ads(id) ON DELETE CASCADE;


--
-- Name: ad_favorites_ip_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_favorites
    ADD CONSTRAINT ad_favorites_ip_id_fkey FOREIGN KEY (ip_id) REFERENCES ips(id) ON DELETE SET NULL;


--
-- Name: ad_favorites_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_favorites
    ADD CONSTRAINT ad_favorites_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;


--
-- Name: ad_form_fields_ad_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_form_fields
    ADD CONSTRAINT ad_form_fields_ad_id_fkey FOREIGN KEY (ad_id) REFERENCES ads(id) ON DELETE CASCADE;


--
-- Name: ad_form_fields_form_field_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_form_fields
    ADD CONSTRAINT ad_form_fields_form_field_id_fkey FOREIGN KEY (form_field_id) REFERENCES form_fields(id) ON DELETE CASCADE;


--
-- Name: ad_packages_category_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_packages
    ADD CONSTRAINT ad_packages_category_id_fkey FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL;


--
-- Name: ad_reports_ad_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_reports
    ADD CONSTRAINT ad_reports_ad_id_fkey FOREIGN KEY (ad_id) REFERENCES ads(id) ON DELETE CASCADE;


--
-- Name: ad_reports_ad_report_type_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_reports
    ADD CONSTRAINT ad_reports_ad_report_type_id_fkey FOREIGN KEY (ad_report_type_id) REFERENCES ad_report_types(id) ON DELETE CASCADE;


--
-- Name: ad_searches_category_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_searches
    ADD CONSTRAINT ad_searches_category_id_fkey FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL;


--
-- Name: ad_searches_ip_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_searches
    ADD CONSTRAINT ad_searches_ip_id_fkey FOREIGN KEY (ip_id) REFERENCES ips(id) ON DELETE CASCADE;


--
-- Name: ad_searches_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_searches
    ADD CONSTRAINT ad_searches_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;


--
-- Name: ad_views_ad_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_views
    ADD CONSTRAINT ad_views_ad_id_fkey FOREIGN KEY (ad_id) REFERENCES ads(id) ON DELETE CASCADE;


--
-- Name: ad_views_ip_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_views
    ADD CONSTRAINT ad_views_ip_id_fkey FOREIGN KEY (ip_id) REFERENCES ips(id) ON DELETE SET NULL;


--
-- Name: ad_views_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ad_views
    ADD CONSTRAINT ad_views_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;


--
-- Name: ads_advertiser_type_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ads
    ADD CONSTRAINT ads_advertiser_type_id_fkey FOREIGN KEY (advertiser_type_id) REFERENCES advertiser_types(id) ON DELETE CASCADE;


--
-- Name: ads_category_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ads
    ADD CONSTRAINT ads_category_id_fkey FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL;


--
-- Name: ads_city_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ads
    ADD CONSTRAINT ads_city_id_fkey FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE SET NULL;


--
-- Name: ads_country_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ads
    ADD CONSTRAINT ads_country_id_fkey FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE SET NULL;


--
-- Name: ads_state_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ads
    ADD CONSTRAINT ads_state_id_fkey FOREIGN KEY (state_id) REFERENCES states(id) ON DELETE SET NULL;


--
-- Name: ads_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ads
    ADD CONSTRAINT ads_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;


--
-- Name: cities_country_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY cities
    ADD CONSTRAINT cities_country_id_fkey FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE SET NULL;


--
-- Name: cities_state_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY cities
    ADD CONSTRAINT cities_state_id_fkey FOREIGN KEY (state_id) REFERENCES states(id) ON DELETE SET NULL;


--
-- Name: form_fields_category_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY form_fields
    ADD CONSTRAINT form_fields_category_id_fkey FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL;


--
-- Name: form_fields_input_type_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY form_fields
    ADD CONSTRAINT form_fields_input_type_id_fkey FOREIGN KEY (input_type_id) REFERENCES input_types(id) ON DELETE CASCADE;


--
-- Name: ips_city_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ips
    ADD CONSTRAINT ips_city_id_fkey FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE SET NULL;


--
-- Name: ips_country_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ips
    ADD CONSTRAINT ips_country_id_fkey FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE SET NULL;


--
-- Name: ips_state_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ips
    ADD CONSTRAINT ips_state_id_fkey FOREIGN KEY (state_id) REFERENCES states(id) ON DELETE SET NULL;


--
-- Name: messages_ad_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY messages
    ADD CONSTRAINT messages_ad_id_fkey FOREIGN KEY (ad_id) REFERENCES ads(id) ON DELETE CASCADE;


--
-- Name: messages_message_content_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY messages
    ADD CONSTRAINT messages_message_content_id_fkey FOREIGN KEY (message_content_id) REFERENCES message_contents(id) ON DELETE CASCADE;


--
-- Name: messages_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY messages
    ADD CONSTRAINT messages_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;


--
-- Name: money_transfer_accounts_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY money_transfer_accounts
    ADD CONSTRAINT money_transfer_accounts_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;


--
-- Name: payment_gateway_settings_payment_gateway_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY payment_gateway_settings
    ADD CONSTRAINT payment_gateway_settings_payment_gateway_id_fkey FOREIGN KEY (payment_gateway_id) REFERENCES payment_gateways(id) ON DELETE CASCADE;


--
-- Name: provider_users_provider_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY provider_users
    ADD CONSTRAINT provider_users_provider_id_fkey FOREIGN KEY (provider_id) REFERENCES providers(id) ON DELETE CASCADE;


--
-- Name: provider_users_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY provider_users
    ADD CONSTRAINT provider_users_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;


--
-- Name: settings_setting_category_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY settings
    ADD CONSTRAINT settings_setting_category_id_fkey FOREIGN KEY (setting_category_id) REFERENCES setting_categories(id) ON DELETE CASCADE;


--
-- Name: states_country_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY states
    ADD CONSTRAINT states_country_id_fkey FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE SET NULL;


--
-- Name: transactions_payment_gateway_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY transactions
    ADD CONSTRAINT transactions_payment_gateway_id_fkey FOREIGN KEY (payment_gateway_id) REFERENCES payment_gateways(id) ON DELETE CASCADE;


--
-- Name: transactions_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY transactions
    ADD CONSTRAINT transactions_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;


--
-- Name: user_ad_extras_ad_extra_day_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_ad_extras
    ADD CONSTRAINT user_ad_extras_ad_extra_day_id_fkey FOREIGN KEY (ad_extra_day_id) REFERENCES ad_extra_days(id) ON DELETE CASCADE;


--
-- Name: user_ad_extras_ad_extra_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_ad_extras
    ADD CONSTRAINT user_ad_extras_ad_extra_id_fkey FOREIGN KEY (ad_extra_id) REFERENCES ad_extras(id) ON DELETE CASCADE;


--
-- Name: user_ad_extras_ad_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_ad_extras
    ADD CONSTRAINT user_ad_extras_ad_id_fkey FOREIGN KEY (ad_id) REFERENCES ads(id) ON DELETE CASCADE;


--
-- Name: user_ad_extras_payment_gateway_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_ad_extras
    ADD CONSTRAINT user_ad_extras_payment_gateway_id_fkey FOREIGN KEY (payment_gateway_id) REFERENCES payment_gateways(id) ON DELETE CASCADE;


--
-- Name: user_ad_extras_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_ad_extras
    ADD CONSTRAINT user_ad_extras_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;


--
-- Name: user_ad_packages_ad_package_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_ad_packages
    ADD CONSTRAINT user_ad_packages_ad_package_id_fkey FOREIGN KEY (ad_package_id) REFERENCES ad_packages(id) ON DELETE CASCADE;


--
-- Name: user_ad_packages_payment_gateway_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_ad_packages
    ADD CONSTRAINT user_ad_packages_payment_gateway_id_fkey FOREIGN KEY (payment_gateway_id) REFERENCES payment_gateways(id) ON DELETE CASCADE;


--
-- Name: user_ad_packages_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_ad_packages
    ADD CONSTRAINT user_ad_packages_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;


--
-- Name: user_cash_withdrawals_money_transfer_account_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_cash_withdrawals
    ADD CONSTRAINT user_cash_withdrawals_money_transfer_account_id_fkey FOREIGN KEY (money_transfer_account_id) REFERENCES money_transfer_accounts(id) ON DELETE CASCADE;


--
-- Name: user_cash_withdrawals_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_cash_withdrawals
    ADD CONSTRAINT user_cash_withdrawals_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;


--
-- Name: user_cash_withdrawals_withdrawal_status_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_cash_withdrawals
    ADD CONSTRAINT user_cash_withdrawals_withdrawal_status_id_fkey FOREIGN KEY (withdrawal_status_id) REFERENCES withdrawal_statuses(id) ON DELETE CASCADE;


--
-- Name: user_notifications_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_notifications
    ADD CONSTRAINT user_notifications_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;


--
-- Name: users_role_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_role_id_fkey FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE;


--
-- Name: vaults_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY vaults
    ADD CONSTRAINT vaults_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- Name: wallets_payment_gateway_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY wallets
    ADD CONSTRAINT wallets_payment_gateway_id_fkey FOREIGN KEY (payment_gateway_id) REFERENCES payment_gateways(id) ON DELETE CASCADE;


--
-- Name: wallets_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY wallets
    ADD CONSTRAINT wallets_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

