import ProductItem from '@/Components/App/ProductItem';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { PageProps, PaginationProps, Product } from '@/types';
import { Head, Link } from '@inertiajs/react';

export default function Home({
    auth,
    products
}: PageProps<{ products: PaginationProps<Product> }>) {
    console.log(products,"FROM HOME");
    return (
        <>
            <AuthenticatedLayout>
                <Head title="Welcome" />
                <div className="hero bg-gray-200  min-h-[550px]">
                    <div className="hero-content text-center">
                        <div className="max-w-md">
                            <h1 className="text-5xl font-bold">Hello there</h1>
                            <p className="py-6">
                                Provident cupiditate voluptatem et in. Quaerat fugiat ut assumenda excepturi exercitationem
                                quasi. In deleniti eaque aut repudiandae et a id nisi.
                            </p>
                            {auth.user ? (
                                <Link href={route('dashboard')} as='button' className="btn btn-primary">Dashboard</Link>
                            ) : <Link href={"/"} as='button' className="btn btn-primary">Home</Link>}

                        </div>
                    </div>
                </div>
                <div className="grid grid-cols-1 ga p-8 md:grid-cols-2 lg-grid-cols-3 p-8">
                    {
                        products.data.map((product) =>
                            <ProductItem product={product} key={product.id} />
                        )
                    }
                </div>
            </AuthenticatedLayout>
        </>
    );
}
